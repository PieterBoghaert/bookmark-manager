<?php

namespace Database\Seeders;

use App\Models\Bookmark;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::query()->firstOrCreate([
            'email' => 'pieter.boghaert@gmail.com',
        ], [
            'name' => 'Pieter Boghaert',
            'password' => 'password',
        ]);

        $payload = json_decode(File::get(database_path('data.json')), true, 512, JSON_THROW_ON_ERROR);

        $tagColors = [
            '#0f766e',
            '#2563eb',
            '#f59e0b',
            '#dc2626',
            '#7c3aed',
            '#059669',
            '#db2777',
            '#4f46e5',
            '#ea580c',
            '#0891b2',
        ];

        DB::transaction(function () use ($payload, $tagColors, $user): void {
            DB::table('bookmark_tag')->delete();
            Bookmark::query()->delete();
            Tag::query()->delete();

            $tagMap = collect($payload['bookmarks'] ?? [])
                ->pluck('tags')
                ->flatten()
                ->filter()
                ->unique()
                ->values()
                ->mapWithKeys(function (string $tagName, int $index) use ($tagColors) {
                    $tag = Tag::query()->create([
                        'name' => $tagName,
                        'color' => $tagColors[$index % count($tagColors)],
                    ]);

                    return [$tagName => $tag->id];
                });

            foreach ($payload['bookmarks'] ?? [] as $bookmarkData) {
                $favicon = $bookmarkData['favicon'] ?? null;

                if (is_string($favicon) && $favicon !== '') {
                    if (!Str::startsWith($favicon, ['http://', 'https://', '/'])) {
                        // Convert JSON relative path like "./assets/images/icon.png" to "/assets/images/icon.png".
                        $favicon = '/' . ltrim($favicon, './');
                    }

                    if (Str::startsWith($favicon, '/') && !File::exists(public_path(ltrim($favicon, '/')))) {
                        $domain = parse_url($bookmarkData['url'] ?? '', PHP_URL_HOST);
                        $favicon = $domain
                            ? "https://www.google.com/s2/favicons?domain={$domain}&sz=64"
                            : null;
                    }
                }

                $bookmark = Bookmark::query()->create([
                    'user_id' => $user->id,
                    'title' => $bookmarkData['title'],
                    'description' => $bookmarkData['description'] ?? null,
                    'url' => $bookmarkData['url'],
                    'favicon' => $favicon,
                    'view_count' => $bookmarkData['visitCount'] ?? 0,
                    'last_visited_at' => $bookmarkData['lastVisited'] ?? null,
                    'is_pinned' => $bookmarkData['pinned'] ?? false,
                    'is_archived' => $bookmarkData['isArchived'] ?? false,
                    'created_at' => $bookmarkData['createdAt'] ?? now(),
                    'updated_at' => $bookmarkData['createdAt'] ?? now(),
                ]);

                $bookmarkTagIds = collect($bookmarkData['tags'] ?? [])
                    ->map(fn(string $tagName) => $tagMap[$tagName] ?? null)
                    ->filter()
                    ->values()
                    ->all();

                $bookmark->tags()->sync($bookmarkTagIds);
            }
        });
    }
}
