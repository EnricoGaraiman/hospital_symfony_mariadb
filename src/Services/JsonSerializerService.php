<?php

namespace App\Services;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class JsonSerializerService
{
    // Convert entity object to json with specific attributes
    public function jsonSerializer($entities, $attributes) {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $json = [];
        foreach ($entities as $entity)
            $json[] = $serializer->normalize($entity, 'json', [AbstractNormalizer::ATTRIBUTES => $attributes]);
        return $json;
    }
}