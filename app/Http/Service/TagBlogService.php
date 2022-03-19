<?php
namespace App\Http\Service;

use App\Models\tagBlog;

class TagBlogService{
    public function createTagBlog($tag = [],$id)
    {
        foreach ($tag as $ar) {
            $tagBlog = new tagBlog();
            $tagBlog->blog_id = $id;
            $tagBlog->tag_id = $ar;
            $tagBlog->save();
        }
    }

    public function EditTagBlog($tag = [],$arrayTagBlog = [],$id)
    {
        if (!empty(array_diff($tag, $arrayTagBlog))) {
            foreach (array_diff($tag, $arrayTagBlog) as $item) {
                $genresProducts = new tagBlog();
                $genresProducts->blog_id = $id;
                $genresProducts->tag_id = $item;
                $genresProducts->save();
            }
        } else {
            foreach (array_diff($arrayTagBlog, $tag) as $item) {
                $arrayTagBlog = tagBlog::where('blog_id', $id)
                    ->where('tag_id', $item)
                    ->delete();
            }
        }
    }
}
?>