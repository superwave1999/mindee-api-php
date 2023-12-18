<?php

/** Carte Vitale V1. */

namespace Mindee\Product\Fr\CarteVitale;

use Mindee\Parsing\Common\Inference;
use Mindee\Parsing\Common\Page;

/**
 * Inference prediction for Carte Vitale, API version 1.
 */
class CarteVitaleV1 extends Inference
{
    /**
     * @var string Name of the endpoint.
     */
    public static string $endpointName = "carte_vitale";
    /**
     * @var string Version of the endpoint.
     */
    public static string $endpointVersion = "1";

    /**
     * @param array $rawPrediction Raw prediction from the HTTP response.
     */
    public function __construct(array $rawPrediction)
    {
        parent::__construct($rawPrediction);
        $this->prediction = new CarteVitaleV1Document($rawPrediction['prediction']);
        $this->pages = [];
        foreach ($rawPrediction['pages'] as $page) {
            $this->pages[] = new Page(CarteVitaleV1Document::class, $page);
        }
    }
}
