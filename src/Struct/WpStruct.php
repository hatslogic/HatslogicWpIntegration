<?php

declare(strict_types=1);

namespace HatslogicWpIntegration\Struct;

use Shopware\Core\Framework\Struct\Struct;

class WpStruct extends Struct
{

    protected $wpPosts = [];
    protected $wpPage = [];

    public function getPosts(): ?array
    {
        return $this->wpPosts;
    }

    public function setPosts(?array $wpPosts): void
    {
        $this->wpPosts = $wpPosts;
    }

    public function getPage(): ?array
    {
        return $this->wpPage;
    }

    public function setPage(?array $wpPage): void
    {
        $this->wpPage = $wpPage;
    }
}
