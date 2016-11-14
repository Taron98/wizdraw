<?php

use Wizdraw\Models\FeedbackQuestion;
use Wizdraw\Models\Group;

/**
 * Class FeedbackQuestionsTableSeeder
 */
class FeedbackQuestionsTableSeeder extends AbstractTableSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FeedbackQuestion::truncate();

        $users = [
            [
                'question' => 'How likely are you to recommend Wizdraw to friends or colleagues?',
            ],
            [
                'question' => 'Does Wizdraw meet your needs?',
            ],
            [
                'question' => 'Do you find Wizdraw easy to use?',
            ],
            [
                'question' => 'Do you manage to save money using Wizdraw?',
            ],
            [
                'question' => 'Did you receive good service at the branch?',
            ],
        ];

        FeedbackQuestion::insert($users);
    }
}
