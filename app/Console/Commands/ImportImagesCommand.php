<?php

namespace App\Console\Commands;

use App\Gallery;
use DOMDocument;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

class ImportImagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'galleries:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import images from external url: http://www.watchthedeer.com/photos';

    /**
     *
     * @var string
     */
    private static $url = 'http://www.watchthedeer.com';

    /**
     * @var array
     */
    private static $unavailableLinks = ['javascript', 'MOV'];

    /**
     * @var array
     */
    private static $unavailableChars = ['../'];

    /**
     * Max numbers of galleries
     */
    const MAX_GALLERIES = 20;

    /**
     * Collection of galleries url
     * @var Collection
     */
    private $galleries;

    private $client;

    private $guzzleClient;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->galleries = collect();
        $this->client = new Client();
        $this->guzzleClient = new GuzzleClient(array(
            'timeout' => 60,
        ));
        $this->client->setClient($this->guzzleClient);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->removeOldGalleries();

        $this->line("Start import galleries");
        $crawler = $this->client->request('GET', self::$url.'/photos');

        $this->line('Preparing links for galleries');
        $crawler->filter('li')->each(function (Crawler $liElements) {
            $liElements->filter('a')->each(function (Crawler $aElements) {
                if (count($this->galleries) === self::MAX_GALLERIES
                    || $this->tryRemoveUnavailableLinks($aElements->attr('href')))
                    return;

                $this->galleries->put($aElements->text(),$aElements->attr('href'));
            });
        });

        $this->line('Start import images from galleries');
        $progress = $this->output->createProgressBar(count($this->galleries));
        foreach ($this->galleries->toArray() as $galleryName => $galleryUrl) {

            $_gallery = Gallery::create(['name' => $galleryName]);

            $fullUrl = self::$url . '/' . $this->tryRemoveUnavailableChars($galleryUrl);
            $crawler = $this->client->request(
                'GET',
                $fullUrl
            );

            $html = $crawler->html();
            $imageNames = collect();
            $countOfImages = substr_count($html, 'myImage[');

            for($i = 0; $i <= $countOfImages-1; $i++) {
                $imageName = Str::substr($html, strpos($html, "myImage[$i] = '"));
                $imageName = Str::substr($imageName, 0, strpos($imageName, "';")+2);
                $html = str_replace($imageName, '', $html);
                $imageName = str_replace("myImage[$i] = '", '', $imageName);
                $imageName = str_replace("';", '', $imageName);
                $imageNames->add($imageName);
            }

            $imageNames->each(function ($n) use ($fullUrl, $galleryName, $_gallery) {
                $imageUrl = str_replace('viewer.aspx', $n, $fullUrl);
                $responseImage = $this->guzzleClient->get($imageUrl);
                Storage::disk('gallery')
                    ->put($galleryName . '/'. $n, $responseImage->getBody()->getContents());
                if ($_gallery instanceof Gallery) {
                    $_gallery->images()->create(['image_name' => $n]);
                }
            });
            $progress->advance();
        }
        $progress->finish();
        $this->output->success('Galleries import complete');
    }

    /**
     * @param string $url
     * @return bool
     */
    public function tryRemoveUnavailableLinks(string $url): bool
    {
        foreach (self::$unavailableLinks as $k => $v) {
            if (Str::contains($url, $v))
                return true;
        }

        return false;
    }

    /**
     * @param string $input
     * @return string|null
     */
    public function tryRemoveUnavailableChars(string $input): ?string
    {
        $output = '';
        foreach (self::$unavailableChars as $unavailableChar) {
            if (Str::contains($input, $unavailableChar))
                $output = str_replace($unavailableChar, '', $input);
        }

        return $output;
    }

    public function removeOldGalleries()
    {
        $galleries = Gallery::all();

        if (count($galleries) > 0) {
            $this->line('Start removing old galleries.');
            $bar = $this->output->createProgressBar(count($galleries));
            foreach ($galleries as $gallery) {
                Storage::deleteDirectory('gallery/'.$gallery['name']);
                $g = Gallery::find($gallery['id']);
                $g->delete();
                $bar->advance();
            }
            $bar->finish();
            $this->line('Old galleries has been removed.');
        }
    }
}
