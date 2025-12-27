<?php

namespace App\Actions\Seeders;

use App\Models\SidebarMenuItem;
use Illuminate\Console\Command;

class CheckSidebarMenuItem
{
    protected Command $command;

    protected array $criteria;

    public function __construct(Command $command, array $criteria)
    {
        $this->command = $command;
        $this->criteria = $criteria;
    }

    public function handle()
    {
        $item = SidebarMenuItem::query()
            ->select([
                'id',
                'title',
                'type',
                'target_type',
                'parent_id',
                'icon_class',
                'route',
                'created_at',
            ])
            ->where($this->criteria)
            ->first();

        if ($item) {
            $this->command->warn('This criteria is already exists');

            $this->command->table(
                array_keys($item->toArray()),
                [array_values($item->toArray())],
            );

            if (! $this->command->confirm('Are you sure to replace with current data?')) {
                $this->command->comment('Bye...');

                exit;
            }
        }
    }
}
