<?php namespace Tyloo\Roster\FormWidgets;

use Backend\Classes\FormWidgetBase;
use System\Models\File;
use SystemException;
use ValidationException;
use Tyloo\Roster\Models\Player;
use Validator;
use Input;
use Response;
use Exception;
use Lang;

/**
 * Preview area for the Create/Edit Player form.
 *
 * @package tyloo\roster
 * @author Julien Bonvarlet
 */
class Preview extends FormWidgetBase
{
    /**
     * {@inheritDoc}
     */
    public function render()
    {
        $this->vars['preview_html'] = Player::formatHtml($this->model->content, true);

        return $this->makePartial('preview');
    }

    public function init()
    {
        $this->checkUploadPlayerback();
    }

    protected function checkUploadPlayerback()
    {
        if (!post('X_PLAYER_IMAGE_UPLOAD'))
            return;

        $uploadedFileName = null;

        try {
            $uploadedFile = Input::file('file');

            if ($uploadedFile)
                $uploadedFileName = $uploadedFile->getClientOriginalName();

            $validationRules = ['max:'.File::getMaxFilesize()];
            $validationRules[] = 'mimes:jpg,jpeg,bmp,png,gif';

            $validation = Validator::make(
                ['file_data' => $uploadedFile],
                ['file_data' => $validationRules]
            );

            if ($validation->fails())
                throw new ValidationException($validation);

            if (!$uploadedFile->isValid())
                throw new SystemException(Lang::get('cms::lang.asset.file_not_valid'));

            $fileRelation = $this->model->content_images();

            $file = new File();
            $file->data = $uploadedFile;
            $file->is_public = true;
            $file->save();

            $fileRelation->add($file, $this->sessionKey);
            $result = [
                'file' => $uploadedFileName,
                'path' => $file->getPath()
            ];

            $response = Response::make()->setContent($result);
            $response->send();

            die();
        } catch (Exception $ex) {
            $message = $uploadedFileName
                ? Lang::get('cms::lang.asset.error_uploading_file', ['name' => $uploadedFileName, 'error' => $ex->getMessage()])
                : $ex->getMessage();

            $result = [
                'error' => $message,
                'file' => $uploadedFileName
            ];

            $response = Response::make()->setContent($result);
            $response->send();

            die();
        }
    }
}
