<?php
/*************************************************************************************/
/*                                                                                   */
/*      Thelia	                                                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*	email : info@thelia.net                                                      */
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
/*	    along with this program. If not, see <http://www.gnu.org/licenses/>.     */
/*                                                                                   */
/*************************************************************************************/

namespace RelatedContent\Loop;

use Propel\Runtime\ActiveQuery\Criteria;
use RelatedContent\Model\RelatedContentQuery;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Content;
use Thelia\Core\Template\Element\LoopResult;

use Thelia\Type;
use Thelia\Type\TypeCollection;

/**
 *
 * Keyword loop
 *
 *
 * Class RelatedContent
 * @package RelatedContent\Loop
 * @author Michaël Espeche <mespeche@openstudio.fr>
 */
class RelatedContent extends Content
{
    protected function getArgDefinitions()
    {
        $argument = parent::getArgDefinitions();

        $argument->addArgument(
            new Argument(
                'content_id',
                new TypeCollection(
                    new Type\IntListType()
                )
            )
        );

        return $argument;

    }

    public function buildModelCriteria()
    {
        $search = parent::buildModelCriteria();

        $relatedContent = RelatedContentQuery::create();
        $results = $relatedContent
            ->findByContentId($this->getContentId())
        ;

        // If RelatedContent doesn't exist
        if (true === $results->isEmpty()) {
            return null;
        }

        $contentIds = array();

        foreach ($results as $result) {

            // If any content is associated with this content ID
            if (null === $result->getRelatedContentId()) {
                return null;
            }

            $contentIds[] = $result->getRelatedContentId();
        }

        if (!empty($contentIds)) {
            $search->filterById($contentIds, Criteria::IN);
        }

        return $search;

    }

    public function parseResults(LoopResult $results)
    {
        $results = parent::parseResults($results);

        return $results;
    }

}
