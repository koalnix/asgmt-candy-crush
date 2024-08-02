<?php

/***************************************************/
// Assignment 2: Candy Crush
// Solution provided by: Nikolche Vishinoski
/***************************************************/

class CandyCrush
{
    protected int $duration = 0;
    protected array $times = [];
    protected ?int $position = NULL;

    // Setters methods
    public function set_duration(int $duration): void
    {
        $this->duration = $duration;
    }
    public function set_times(array $times): void
    {
        $this->times = $times;
    }
    public function set_position(int $position): void
    {
        $this->position = $position;
    }

    // Getter methods
    public function get_duration(): int
    {
        return $this->duration;
    }
    public function get_times(): array
    {
        return $this->times;
    }
    public function get_position(): int
    {
        return $this->position;
    }

    // Gets the time left for a specific position provided
    public function get_time_at(int $position): ?int
    {
        return isset($this->times[$position]) ? $this->times[$position] : NULL;
    }

    // Gets the time left for the candy
    public function get_timeleft(): int
    {
        return $this->get_time_at($this->get_position());
    }

    // Methods

    /**
    Calculates the maximum timeleft for a specific way/delta
    @param int $delta Should be either `1` for right or `-1` for left
    @return int The maximum timeleft for the way
    */
    public function calculate_max_timeleft(int $delta): int
    {
        $max_time = 0;
        $relative_delta = 0;
        while (true) {
            $relative_delta += $delta;
            if ($this->get_time_at($this->get_position() + $relative_delta) == NULL) break;
            if ($this->get_time_at($this->get_position() + $relative_delta) - 1 < abs($relative_delta)) break;
            $max_time = max($max_time, $this->get_time_at($this->get_position() + $relative_delta));
        }

        return $max_time;
    }

    /**
    Calculates the recommended way to go in order the candy to surivive.
    @return int Returns `left`, `right`, or empty string if should not move
    */
    public function recomended_way(): string
    {
        $left_position_time = $this->calculate_max_timeleft(-1);
        $right_position_time = $this->calculate_max_timeleft(1);
        if (max($left_position_time, $right_position_time) > $this->get_timeleft()) {
            if ($left_position_time > $right_position_time) {
                if ($this->get_position() - 1 >= 0) {
                    return 'left';
                }
            } else {
                if ($this->get_position() + 1 < count($this->get_times())) {
                    return 'right';
                }
            }
        }

        return '';
    }

    /**
    Calculates the movement delta
    @return int The movement delta
    */
    public function calculate_movement(): int
    {
        $delta = 0;

        switch ($this->recomended_way()) {
            case 'left':
                $delta--;
                break;
            case 'right':
                $delta++;
        }


        return $delta;
    }

    /**
    Processes the next iteration and decreases the time for every position
    */
    public function next_iteration(): void
    {
        $this->set_position($this->get_position() + $this->calculate_movement());

        for ($i = 0; $i < count($this->get_times()); $i++) {
            if ($this->times[$i] > 0) {
                $this->times[$i]--;
            }
        }
        $this->set_duration($this->get_duration() + 1);
    }

    /**
    Calculates and returns the number of iterations it takes for the candy to get crushed
    using an alghorithm to find the best path and survive the longest.
    @param array $times The array of times
    @param int $position The current position of the candy
     */
    public static function how_long(array $times, int $position): int
    {
        $candy = new self();
        $candy->set_times($times);
        $candy->set_position($position);

        while ($candy->get_timeleft() > 0) {
            $candy->next_iteration();
        }

        return $candy->get_duration();
    }
}

// Running tests
function run_tests(): void
{
    echo CandyCrush::how_long([1, 2, 3, 4], 0);
    echo "<br/>";
    echo CandyCrush::how_long([1, 2, 10, 4], 0);
    echo "<br/>";
    echo CandyCrush::how_long([10, 1, 3, 4, 7], 2);
    echo "<br/>";
    echo CandyCrush::how_long([10, 2, 3, 4, 7], 2);
    echo "<br/>";
    echo CandyCrush::how_long([3, 3, 1, 3, 4, 4, 1, 3], 7);
    echo "<br/>";
    echo CandyCrush::how_long([1, 2, 4, 3, 4, 3, 1, 3, 3, 4], 1);
    echo "<br/>";
    echo CandyCrush::how_long([2, 1, 4, 4, 1, 1, 1, 1, 2, 1], 6);
    echo "<br/>";
    echo CandyCrush::how_long([
        950, 501, 913, 2, 636, 287, 753, 5, 126, 1, 305, 2, 712, 3, 1, 5, 4, 26, 715, 532, 2, 4, 98, 3, 296, 4, 184, 1, 154, 541, 2, 4, 2, 141, 577, 376, 67, 3, 424, 360, 521, 5, 4, 5, 4, 886, 3, 5, 5, 334
    ], 28);
    echo "<br/>";
    echo CandyCrush::how_long([
        2, 4, 2, 4, 803, 1, 996, 855, 682, 3, 2, 5, 1, 5, 225, 3, 4, 5, 49, 189, 3, 328, 5, 494, 863, 390, 2, 1, 810, 4,
        819, 5, 4, 645, 691, 5, 279, 82, 202, 368, 546, 1, 1, 2, 488, 4, 163, 2, 487, 486
    ], 12);
    echo "<br/>";
    echo CandyCrush::how_long([
        288, 1, 256, 327, 723, 432, 674, 196, 218, 90, 6, 563, 643, 431, 351, 948, 546, 282, 705, 805, 864, 229, 99, 499, 865, 986, 218, 961, 434, 12, 338, 255, 91, 797, 406, 519, 242, 329, 578, 220, 912, 866, 702, 41,
        2, 456, 430, 702, 688, 397, 222, 792, 153, 155, 784, 957, 413, 401, 167, 76, 586, 429, 306, 124, 498, 136, 25,
        8, 152, 752, 660, 136, 160, 378, 771, 358, 861, 296, 658, 988, 173, 740, 350, 879, 32, 362, 597, 125, 345, 2, 1,
        93, 420, 417, 51, 808, 195, 169, 50, 703, 505, 327, 579
    ], 0);
}

run_tests();
