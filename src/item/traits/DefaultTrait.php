<?php

namespace customiesdevs\customies\item\traits;

trait DefaultTrait {
    use ItemComponentsTrait;

    protected function initComponent(): void {
        // Default items do not have any components for now.
    }

}