<?php

namespace App\Models;

use JetBrains\PhpStorm\ArrayShape;

class SurveysModel extends Json
{
    public function getSurveys($keyword = '')
    {
        $data = $this->data;

        if (!empty($keyword)) {
            $data = $data->filter(
                function ($q) use ($keyword) {
                    return (strtoupper($q['survey']['name']) == strtoupper($keyword) || strtoupper($q['survey']['code']) == strtoupper($keyword));
                }
            );
        }
        return $data->groupBy('survey.code')->map(function ($group) {
            return [
                'number_of_answer' => $group->count(),
                'survey_name' => $group->first()['survey']['name'],
            ];
        });
    }

    public function find($code): array
    {
        $collection = collect($this->data)->where('survey.code', $code);
        return $collection->flatMap(function ($item) {
            return $item['questions'];
        })->groupBy('label')->map(function ($item) {
            $question = $item->first();
            $type = $question['type'];
            $label = $question['label'];
            return match ($type) {
                'qcm' => $this->transformQCMQuestion($question['options'], $item, $type, $label),
                'numeric' => $this->transformNumericQuestion($item, $type, $label),
                default => null,
            };
        })->values()->all();
    }

    /**
     * @param $options1
     * @param $item
     * @param string $type
     * @param mixed $label
     * @return array
     */
    #[ArrayShape(['type' => "string", 'label' => "mixed", 'result' => ""])]
    protected function transformQCMQuestion($options1, $item, string $type, mixed $label): array
    {
        $options = $options1;
        $result = array_reduce($item->pluck('answer')->toArray(), function ($carry, $answers) use ($options) {
            foreach ($options as $index => $option) {
                $carry[$option] = isset($carry[$option]) ? $carry[$option] + $answers[$index] : $answers[$index];
            }
            return $carry;
        }, []);

        return [
            'type' => $type,
            'label' => $label,
            'result' => $result,
        ];
    }

    /**
     * @param $item
     * @param mixed $type
     * @param mixed $label
     * @return array
     */
    #[ArrayShape(['type' => "mixed", 'label' => "mixed", 'result' => "mixed"])]
    protected function transformNumericQuestion($item, mixed $type, mixed $label): array
    {
        $result = $item->avg('answer');

        return [
            'type' => $type,
            'label' => $label,
            'result' => $result,
        ];
    }
}
