<?php


namespace App\Services\Self;


class UpdateTask extends BaseTask implements TaskInterface
{
    public function generate()
    {
       $tags = $this->request("https://api.github.com/repos/lifeeka/born/tags");
       if($tags){
           
       }
    }
}