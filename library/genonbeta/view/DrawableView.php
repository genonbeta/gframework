<?php

namespace genonbeta\view;

use genonbeta\util\HashMap;

interface DrawableView
{
    public function draw(array $items);
    public function drawAsAdapter(HashMap $items);
}
