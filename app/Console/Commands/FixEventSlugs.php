<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use Illuminate\Support\Str;

class FixEventSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:fix-slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate slugs for events that don\'t have one';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing event slugs...');
        
        $events = Event::whereNull('slug')->orWhere('slug', '')->get();
        
        if ($events->isEmpty()) {
            $this->info('All events already have slugs!');
            return;
        }
        
        $count = 0;
        foreach ($events as $event) {
            $event->slug = Str::slug($event->title);
            $event->save();
            $count++;
            $this->info("Fixed: {$event->title} -> {$event->slug}");
        }
        
        $this->info("✅ Fixed {$count} event(s) successfully!");
    }
}
