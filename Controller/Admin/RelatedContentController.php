<?php
/**
 * Created by PhpStorm.
 * User: michael-espeche
 * Date: 06/05/2014
 * Time: 14:46
 */

namespace RelatedContent\Controller\Admin;


use Propel\Runtime\Exception\PropelException;
use RelatedContent\EventListeners\RelatedContent;
use RelatedContent\Form\RelatedContentForm;
use Thelia\Controller\Admin\AbstractCrudController;
use Thelia\Controller\Admin\Response;
use Thelia\Controller\Admin\unknown;
use Thelia\Core\Security\AccessManager;
use Thelia\Form\Exception\FormValidationException;
use Thelia\Model\ContentQuery;

class RelatedContentController extends AbstractCrudController {

    public function __construct()
    {
        parent::__construct(
            'related_content',
            null,
            null,
            'admin.related_content',
            null,
            null,
            RelatedContent::RELATED_CONTENT_ASSOCIATION_DELETE
        );
    }

    public function updateRelatedContentAssociation($content_id) {

        if (null !== $response = $this->checkAuth(array(), array('RelatedContent'), AccessManager::UPDATE)) {
            return $response;
        }

        $relatedContentForm = new RelatedContentForm($this->getRequest());

        $message = false;

        try {

            $content = ContentQuery::create()->findPk($content_id);

            if (null === $content) {
                throw new \InvalidArgumentException(sprintf("%d content id does not exist", $content_id));
            }

            $form = $this->validateForm($relatedContentForm);

            $event = $this->createEventInstance($form->getData());
            $event->setContentId($content_id);

            $this->dispatch(RelatedContent::RELATED_CONTENT_ASSOCIATION, $event);

            $this->redirectSuccess($relatedContentForm);

        } catch (FormValidationException $e) {
            $message = sprintf("Please check your input: %s", $e->getMessage());
        } catch (PropelException $e) {
            $message = $e->getMessage();
        } catch (\Exception $e) {
            $message = sprintf("Sorry, an error occured: %s", $e->getMessage()." ".$e->getFile());
        }

        if ($message !== false) {
            \Thelia\Log\Tlog::getInstance()->error(sprintf("Error during related content association process : %s.", $message));

            $relatedContentForm->setErrorMessage($message);

            $this->getParserContext()
                ->addForm($relatedContentForm)
                ->setGeneralError($message)
            ;
        }

        // Redirect to current contebt
        $this->redirectToRoute(
            'admin.content.update',
            array(),
            array('content_id' => $content_id, 'current_tab' => 'modules')
        );

    }

    private function createEventInstance($data)
    {
        $relatedContentEvent = new RelatedContent($data['content']);

        return $relatedContentEvent;
    }

    /*protected function performAdditionalDeleteAction($deleteEvent)
    {

    }*/

    /**
     * Return the creation form for this object
     */
    protected function getCreationForm()
    {
        // TODO: Implement getCreationForm() method.
    }

    /**
     * Return the update form for this object
     */
    protected function getUpdateForm()
    {
        // TODO: Implement getUpdateForm() method.
    }

    /**
     * Hydrate the update form for this object, before passing it to the update template
     *
     * @param unknown $object
     */
    protected function hydrateObjectForm($object)
    {
        // TODO: Implement hydrateObjectForm() method.
    }

    /**
     * Creates the creation event with the provided form data
     *
     * @param unknown $formData
     */
    protected function getCreationEvent($formData)
    {
        // TODO: Implement getCreationEvent() method.
    }

    /**
     * Creates the update event with the provided form data
     *
     * @param unknown $formData
     */
    protected function getUpdateEvent($formData)
    {
        // TODO: Implement getUpdateEvent() method.
    }

    /**
     * Creates the delete event with the provided form data
     */
    protected function getDeleteEvent()
    {
        return new RelatedContent($this->getRequest()->get('related_content_id'), $this->getRequest()->get('content_id'));
    }

    /**
     * Return true if the event contains the object, e.g. the action has updated the object in the event.
     *
     * @param unknown $event
     */
    protected function eventContainsObject($event)
    {
        // TODO: Implement eventContainsObject() method.
    }

    /**
     * Get the created object from an event.
     *
     * @param unknown $event
     */
    protected function getObjectFromEvent($event)
    {
        // TODO: Implement getObjectFromEvent() method.
    }

    /**
     * Load an existing object from the database
     */
    protected function getExistingObject()
    {
        // TODO: Implement getExistingObject() method.
    }

    /**
     * Returns the object label form the object event (name, title, etc.)
     *
     * @param unknown $object
     */
    protected function getObjectLabel($object)
    {
        // TODO: Implement getObjectLabel() method.
    }

    /**
     * Returns the object ID from the object
     *
     * @param unknown $object
     */
    protected function getObjectId($object)
    {
        // TODO: Implement getObjectId() method.
    }

    /**
     * Render the main list template
     *
     * @param unknown $currentOrder , if any, null otherwise.
     */
    protected function renderListTemplate($currentOrder)
    {
        // TODO: Implement renderListTemplate() method.
    }

    /**
     * Render the edition template
     */
    protected function renderEditionTemplate()
    {
        // TODO: Implement renderEditionTemplate() method.
    }

    /**
     * Redirect to the edition template
     */
    protected function redirectToEditionTemplate()
    {
        // TODO: Implement redirectToEditionTemplate() method.
    }

    /**
     * Redirect to the list template
     */
    protected function redirectToListTemplate()
    {
        $contentId = $this->getRequest()->get('content_id');
        $this->redirectToRoute(
            'admin.content.update',
            array(),
            array('content_id' => $contentId, 'current_tab' => 'modules')
        );
    }


} 