<?php

/*
 * This file is part of Laravel Throttle.
 *
 * (c) Graham Campbell <graham@cachethq.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Throttle\Factories;

use GrahamCampbell\Throttle\Data;

/**
 * This is the throttler factory interface.
 *
 * @author Graham Campbell <graham@cachethq.io>
 */
interface FactoryInterface
{
    /**
     * Make a new throttler instance.
     *
     * @param \GrahamCampbell\Throttle\Data $data
     *
     * @return \GrahamCampbell\Throttle\Throttlers\ThrottlerInterface
     */
    public function make(Data $data);
}
