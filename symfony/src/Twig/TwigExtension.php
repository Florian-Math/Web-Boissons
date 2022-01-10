<?php

namespace App\Twig;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension
{
    private $kernel;
    private $requestStack;
    private $doctrine;

    public function __construct(KernelInterface $kernel, RequestStack $requestStack, ManagerRegistry $doctrine)
    {
        $this->kernel = $kernel;
        $this->requestStack = $requestStack;
        $this->doctrine = $doctrine;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('asset_exists', [$this, 'asset_exists']),
            new TwigFunction('is_favorite', [$this, 'isFavorite']),
        );
    }

    public function getFilters()
    {
        return [
            new TwigFilter('toImgPath', [$this, 'titleToImgPath']),
        ];
    }

    public function isFavorite($id){
        if($this->requestStack->getSession()->get('user')) $favs = $this->requestStack->getSession()->get('user')->getFavorites();
        else $favs = isset($_COOKIE['favorites']) ? json_decode($_COOKIE['favorites']) : [];

        return in_array($id, $favs);
    }

    public function asset_exists($path){
        $webRoot = realpath($this->kernel->getProjectDir() . '/web/');
        $toCheck = realpath($webRoot . $path);

        // check if the file exists
        if (!is_file($toCheck))
        {
            return false;
        }

        // check if file is well contained in web/ directory (prevents ../ in paths)
        if (strncmp($webRoot, $toCheck, strlen($webRoot)) !== 0)
        {
            return false;
        }

        return true;
    }

    public function titleToImgPath($title){
        return ucfirst(strtolower($this->cleanString(str_replace(' ', '_', $title))));
    }

    private function cleanString($text) { // source : https://stackoverflow.com/questions/14114411/remove-all-special-characters-from-a-string
        $utf8 = array(
            '/[áàâãªä]/u'   =>   'a',
            '/[ÁÀÂÃÄ]/u'    =>   'A',
            '/[ÍÌÎÏ]/u'     =>   'I',
            '/[íìîï]/u'     =>   'i',
            '/[éèêë]/u'     =>   'e',
            '/[ÉÈÊË]/u'     =>   'E',
            '/[óòôõºö]/u'   =>   'o',
            '/[ÓÒÔÕÖ]/u'    =>   'O',
            '/[úùûü]/u'     =>   'u',
            '/[ÚÙÛÜ]/u'     =>   'U',
            '/ç/'           =>   'c',
            '/Ç/'           =>   'C',
            '/ñ/'           =>   'n',
            '/Ñ/'           =>   'N',
            '/–/'           =>   '-', // UTF-8 hyphen to "normal" hyphen
            '/[’‘‹›‚]/u'    =>   ' ', // Literally a single quote
            '/[“”«»„]/u'    =>   ' ', // Double quote
            '/ /'           =>   ' ', // nonbreaking space (equiv. to 0x160)
            '/\'/'          =>   '' ,
        );
        return preg_replace(array_keys($utf8), array_values($utf8), $text);
    }
}