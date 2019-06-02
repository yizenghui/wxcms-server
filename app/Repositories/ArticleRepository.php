<?php 
namespace App\Repositories;

use Bosnadev\Repositories\Contracts\RepositoryInterface;
use Bosnadev\Repositories\Eloquent\Repository;

class ArticleRepository extends Repository {

    public function model() {
        return 'App\Models\Article';
    }
}