<?php
/*************************************************************************************/
/*                                                                                   */
/*      Thelia	                                                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : info@thelia.net                                                      */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      This program is free software; you can redistribute it and/or modify         */
/*      it under the terms of the GNU General Public License as published by         */
/*      the Free Software Foundation; either version 3 of the License                */
/*                                                                                   */
/*      This program is distributed in the hope that it will be useful,              */
/*      but WITHOUT ANY WARRANTY; without even the implied warranty of               */
/*      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                */
/*      GNU General Public License for more details.                                 */
/*                                                                                   */
/*      You should have received a copy of the GNU General Public License            */
/*	    along with this program. If not, see <http://www.gnu.org/licenses/>.         */
/*                                                                                   */
/*************************************************************************************/

namespace RelatedContent\Action;

use RelatedContent\Model\Base\RelatedContentQuery;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use RelatedContent\EventListeners\RelatedContent as RelatedContentEvent;
use RelatedContent\Model\RelatedContent as RelatedContentModel;

/**
 *
 * RelatedContent class where all actions are managed
 *
 * Class RelatedContent
 * @package RelatedContent\Action
 * @author Michaël Espeche <mespeche@openstudio.fr>
 */
class RelatedContent implements EventSubscriberInterface
{

    public function relatedContentAssociation(RelatedContentEvent $event) {

        $relatedContentAssociation = new RelatedContentModel();
        $relatedContentAssociation
            ->setContentId($event->getContentId())
            ->setRelatedContentId($event->getContent())
            ->save();

    }

    public function relatedContentAssociationDelete (RelatedContentEvent $event) {

        $ids = array($event->getContentId(),$event->getContent());

        if (null !== $relatedContent = RelatedContentQuery::create()->findPks(array($ids))) {
            $relatedContent->delete();
        }
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(
            RelatedContentEvent::RELATED_CONTENT_ASSOCIATION => array('relatedContentAssociation', 128),
            RelatedContentEvent::RELATED_CONTENT_ASSOCIATION_DELETE => array('relatedContentAssociationDelete', 128)
        );
    }
}
