<?php

use Codice\Note;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use League\CommonMark\CommonMarkConverter;

class CacheParsedContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->text('content_raw')->afert('content');
        });

        // Migrate data to the new format
        foreach (Note::all() as $note)
        {
            $converter = new CommonMarkConverter();

            $content_raw = $note->content_raw;
            $content_parsed = $converter->convertToHtml($content_raw);

            $note->content = $content_parsed;
            $note->content_raw = $content_raw;
            $note->save();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Migrate data back to the old format
        foreach (Note::all() as $note)
        {
            $note->content = $note->content_raw;
            $note->save();
        }

        Schema::table('notes', function (Blueprint $table) {
            $table->dropColumn('content_raw');
        });
    }
}
