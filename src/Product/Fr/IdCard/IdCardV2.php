<?php

/** Carte Nationale d'Identité V2. */

namespace Mindee\Product\Fr\IdCard;

use Mindee\Parsing\Common\Inference;
use Mindee\Parsing\Common\Page;

/**
 * Inference prediction for Carte Nationale d'Identité, API version 2.
 */
class IdCardV2 extends Inference
{
    /**
     * @var string Name of the endpoint.
     */
    public static string $endpointName = "idcard_fr";
    /**
     * @var string Version of the endpoint.
     */
    public static string $endpointVersion = "2";

    /**
     * @param array $rawPrediction Raw prediction from the HTTP response.
     */
    public function __construct(array $rawPrediction)
    {
        parent::__construct($rawPrediction);
        $this->prediction = new IdCardV2Document($rawPrediction['prediction']);
        $this->pages = [];
        foreach ($rawPrediction['pages'] as $page) {
            $this->pages[] = new Page(IdCardV2Page::class, $page);
        }
    }
}
