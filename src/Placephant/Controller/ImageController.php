<?php

namespace Placephant\Controller;

use Imanee\Exception\FilterNotFoundException;
use Imanee\Imanee;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @package Placephant
 */
class ImageController extends \Flint\Controller\Controller
{
    /**
     * @param integer $width
     * @param integer $height
     * @param Request $request
     * @return Response
     */
    public function showAction($width, $height = 0, Request $request)
    {
        $config = $this->get('config');
        $resource = $this->get('resources')->getRandom();

        $height = min(abs($height ?: $width), $config['max_height']);
        $width = min(abs($width), $config['max_width']);

        $imanee = (new Imanee($resource))
            ->thumbnail($width, $height, true);

        if ($request->query->has('filter')) {
            try {
                $imanee->applyFilter('filter_' . $request->query->get('filter'));
            } catch (FilterNotFoundException $e) {};
        }

        return $this->text($imanee->output(), 200, array(
            'Content-Type' => 'image/jpg')
        );
    }
}
