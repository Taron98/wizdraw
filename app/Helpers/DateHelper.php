<?php

use Carbon\Carbon;

if (!function_exists('diff_hours_user_and_project')) {
    /**
     * Get hours offset of project's timezone and input offset.
     *
     * @param int $offset
     *
     * @return int
     */
    function diff_hours_user_and_project($offset = 0)
    {
        if (is_null($offset)) {
            $offset = 0;
        }

        // Convert from seconds to hours
        $projectOffset = Carbon::now()->getOffset() / 3600;

        return ($projectOffset - $offset);
    }
}