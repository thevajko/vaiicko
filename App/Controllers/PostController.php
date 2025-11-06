<?php

namespace App\Controllers;

use App\Configuration;
use App\Models\Post;
use Exception;
use Framework\Core\BaseController;
use Framework\Http\HttpException;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

class PostController extends BaseController
{
    /**
     * Authorization method
     *
     * @param Request $request
     * @param string $action
     * @return bool
     * @throws Exception
     */
    public function authorize(Request $request, string $action): bool
    {
        switch ($action) {
            case 'edit' :
            case 'delete' :
                // get id of post to check
                $id = (int)$request->value("id");
                // get post from db
                $postToCheck = Post::getOne($id);
                // check if the logged login is the same as the post author
                // if yes, he can edit and delete post
                return $postToCheck->getAuthor() == $this->app->getAuth()->user->getName();
            case 'save':
                // get id of post to check
                $id = (int)$request->value("id");
                if ($id > 0 ) {
                    // only author can save the edited post
                    $postToCheck = Post::getOne($id);
                    return $postToCheck->getAuthor() == $this->app->getAuth()->user->getName();
                } else {
                    // anyone can add a new post
                    return $this->app->getAuth()->isLogged();
                }
            default:
                return $this->app->getAuth()->isLogged();
        }
    }

    /**
     * Example of an action (authorization needed)
     *
     * @param Request $request
     * @return Response
     * @throws HttpException
     */
    public function index(Request $request): Response
    {
        try {
            return $this->html(
                [
                    'posts' => Post::getAll()
                ]
            );
        } catch (Exception $e) {
            throw new HttpException(500, "DB Chyba: " . $e->getMessage());
        }
    }

    /**
     * Add post
     *
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response
    {
        return $this->html();
    }

    /**
     * Edit post
     *
     * @param Request $request
     * @return Response
     * @throws HttpException
     */
    public function edit(Request $request): Response
    {
        $id = (int)$request->value('id');
        $post = Post::getOne($id);

        if (is_null($post)) {
            throw new HttpException(404);
        }
        return $this->html(compact('post'));
    }

    /**
     * Save post
     *
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function save(Request $request): Response
    {
        $id = (int)$request->value('id');
        $oldFileName = "";

        if ($id > 0) {
            $post = Post::getOne($id);
            $oldFileName = $post->getPicture();
        } else {
            $post = new Post();
        }
        $post->setAuthor($this->app->getAuth()->user->getName());
        $post->setText($request->value('text'));
        // Do not set original name; we'll generate a unique one below after validation and store

        $formErrors = $this->formErrors($request);
        if (count($formErrors) > 0) {
            return $this->html(
                compact('post', 'formErrors'),
                ($id > 0) ? 'edit' : 'add'
            );
        } else {
            // Ensure upload directory exists
            if (!is_dir(Configuration::UPLOAD_DIR)) {
                if (!@mkdir(Configuration::UPLOAD_DIR, 0777, true) && !is_dir(Configuration::UPLOAD_DIR)) {
                    throw new HttpException(500, 'Nepodarilo sa vytvoriť adresár pre nahrávanie súborov.',);
                }
            }

            // Remove old file if present
            if ($oldFileName != "") {
                $oldPath = Configuration::UPLOAD_DIR . $oldFileName;
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }

            // Generate unique file name and store uploaded file
            $newFile = $request->file('picture');
            $uniqueName = time() . '-' . $newFile->getName();
            $targetPath = Configuration::UPLOAD_DIR . $uniqueName;

            if (!$newFile->store($targetPath)) {
                throw new HttpException(500, 'Chyba pri ukladaní súboru.');
            }

            $post->setPicture($uniqueName);

            try {
                $post->save();
            } catch (Exception $e) {
                // Best-effort cleanup of stored file if DB save fails
                if (is_file($targetPath)) {
                    @unlink($targetPath);
                }
                throw new Exception('DB Chyba: ' . $e->getMessage());
            }
            return $this->redirect($this->url("post.index"));
        }
    }

    /**
     * Delete post
     *
     * @param Request $request
     * @return Response
     * @throws HttpException
     */
    public function delete(Request $request): Response
    {
        try {
            $id = (int)$request->value('id');
            $post = Post::getOne($id);

            if (is_null($post)) {
                throw new HttpException(404);
            }
            @unlink(Configuration::UPLOAD_DIR . $post->getPicture());
            $post->delete();

        } catch (Exception $e) {
            throw new HttpException(500, 'DB Chyba: ' . $e->getMessage());
        }

        return $this->redirect($this->url("post.index"));
    }

    /**
     * Form validation
     *
     * @param Request $request
     * @return array
     */
    private function formErrors(Request $request): array
    {
        $errors = [];
        if ($request->file('picture')->getName() == "") {
            $errors[] = "Pole Súbor obrázka musí byť vyplnené!";
        }
        if ($request->value('text') == "") {
            $errors[] = "Pole Text príspevku musí byť vyplnené!";
        }
        if ($request->file('picture')->getName() != "" &&
            !in_array($request->file('picture')->getType(), ['image/jpeg', 'image/png'])) {
            $errors[] = "Obrázok musí byť typu JPG alebo PNG!";
        }
        if ($request->value('text') != "" && strlen($request->value('text') < 5)) {
            $errors[] = "Počet znakov v text príspevku musí byť viac ako 5!";
        }
        return $errors;
    }
}
