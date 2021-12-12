<?php

namespace Database\Seeders;

use App\Entity\ProjectMapAttachment;
use Illuminate\Database\Seeder;

class ProjectMapAttachmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = new ProjectMapAttachment();
        $file->id_project = 88;
        $file->attachment_type = 'social';
        $file->file_type = 'PDF';
        $file->original_filename = 'social.pdf';
        $file->stored_filename = 'peta_social_88.pdf';
        $file->save();
        //
    }
}
