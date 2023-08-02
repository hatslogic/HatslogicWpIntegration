<?php

namespace HatslogicWpIntegration\Service;

class ContentFormatter
{
    public function formatContent(array $response): array
    {
        if (empty($response)) {
            return [];
        }

        $featuredMedia = $response[0]['_embedded']['wp:featuredmedia'][0]['source_url'] ?? null;
        $content = $response[0]['content']['rendered'] ?? '';
        $content = str_replace('\n', '<br>', $content);
        $content = str_replace('\t', '<<TAB>>', $content);
        $finalcontent = str_replace("\t", '<span></span>', $content);
        $content = str_replace('<<TAB>>', "\t", $finalcontent);

        return [
            'date' => $response[0]['date'],
            'title' => $response[0]['title']['rendered'],
            'image' => $featuredMedia,
            'content' => $content,
        ];
    }

    public function formatBlogList(array $postsDatas, int $titleLimit): array
    {
        $formattedData = [];

        foreach ($postsDatas as $postsData) {
            if (mb_strlen($postsData['title']['rendered']) > $titleLimit) {
                $truncatedTitle = mb_substr($postsData['title']['rendered'], 0, intval($titleLimit)) . '...';
                $title = $truncatedTitle;
            } else {
                $title = $postsData['title']['rendered'];
            }

            $record = [
                'date' => $postsData['date'],
                'slug' => $postsData['slug'],
                'title' => $title,
            ];

            if (isset($postsData['_embedded']['wp:featuredmedia'][0]['media_details']['sizes']['medium']['source_url'])) {
                $record['image'] = $postsData['_embedded']['wp:featuredmedia'][0]['media_details']['sizes']['medium']['source_url'];
            } else {
                $record['image'] = '/bundles/hatslogicwpintegration/static/cms/wp-post-default.jpg';
            }

            $formattedData[] = $record;
        }

        return $formattedData;
    }
}
