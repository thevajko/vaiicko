<?php

namespace App\Controllers;

use App\Models\Post;
use Framework\Core\BaseController;
use Framework\Http\HttpException;
use Framework\Http\Request;
use Framework\Http\Responses\RedirectResponse;
use Framework\Http\Responses\Response;

/**
 * Controller to handle likes on posts
 */
class LikeController extends BaseController
{
    /**
     * Authorization method
     *
     * @param Request $request
     * @param string $action
     * @return bool
     */
    public function authorize(Request $request, string $action): bool
    {
        return $this->app->getAuth()->isLogged();
    }
    public function index(Request $request): Response
    {
        throw new HTTPException(501);
    }

    /**
     * Toggle like on a post
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws HttpException
     */
    public function toggle(Request $request){
        // get id of post to toggle like
        $id = $request->value("id");
        // get post from db
        $postToLike = Post::getOne($id);
        // toggle like
        $postToLike->likeToggle($this->app->getAuth()->user?->getName());
        // redirect to home
        return new RedirectResponse($this->url('post.index'));
    }
}
