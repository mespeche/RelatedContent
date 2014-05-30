<?php

namespace RelatedContent\Model\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use RelatedContent\Model\RelatedContent as ChildRelatedContent;
use RelatedContent\Model\RelatedContentQuery as ChildRelatedContentQuery;
use RelatedContent\Model\Map\RelatedContentTableMap;
use RelatedContent\Model\Thelia\Model\Content;

/**
 * Base class that represents a query for the 'related_content' table.
 *
 *
 *
 * @method     ChildRelatedContentQuery orderByContentId($order = Criteria::ASC) Order by the content_id column
 * @method     ChildRelatedContentQuery orderByRelatedContentId($order = Criteria::ASC) Order by the related_content_id column
 * @method     ChildRelatedContentQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildRelatedContentQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildRelatedContentQuery groupByContentId() Group by the content_id column
 * @method     ChildRelatedContentQuery groupByRelatedContentId() Group by the related_content_id column
 * @method     ChildRelatedContentQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildRelatedContentQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildRelatedContentQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildRelatedContentQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildRelatedContentQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildRelatedContentQuery leftJoinContentRelatedByContentId($relationAlias = null) Adds a LEFT JOIN clause to the query using the ContentRelatedByContentId relation
 * @method     ChildRelatedContentQuery rightJoinContentRelatedByContentId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ContentRelatedByContentId relation
 * @method     ChildRelatedContentQuery innerJoinContentRelatedByContentId($relationAlias = null) Adds a INNER JOIN clause to the query using the ContentRelatedByContentId relation
 *
 * @method     ChildRelatedContentQuery leftJoinContentRelatedByRelatedContentId($relationAlias = null) Adds a LEFT JOIN clause to the query using the ContentRelatedByRelatedContentId relation
 * @method     ChildRelatedContentQuery rightJoinContentRelatedByRelatedContentId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ContentRelatedByRelatedContentId relation
 * @method     ChildRelatedContentQuery innerJoinContentRelatedByRelatedContentId($relationAlias = null) Adds a INNER JOIN clause to the query using the ContentRelatedByRelatedContentId relation
 *
 * @method     ChildRelatedContent findOne(ConnectionInterface $con = null) Return the first ChildRelatedContent matching the query
 * @method     ChildRelatedContent findOneOrCreate(ConnectionInterface $con = null) Return the first ChildRelatedContent matching the query, or a new ChildRelatedContent object populated from the query conditions when no match is found
 *
 * @method     ChildRelatedContent findOneByContentId(int $content_id) Return the first ChildRelatedContent filtered by the content_id column
 * @method     ChildRelatedContent findOneByRelatedContentId(int $related_content_id) Return the first ChildRelatedContent filtered by the related_content_id column
 * @method     ChildRelatedContent findOneByCreatedAt(string $created_at) Return the first ChildRelatedContent filtered by the created_at column
 * @method     ChildRelatedContent findOneByUpdatedAt(string $updated_at) Return the first ChildRelatedContent filtered by the updated_at column
 *
 * @method     array findByContentId(int $content_id) Return ChildRelatedContent objects filtered by the content_id column
 * @method     array findByRelatedContentId(int $related_content_id) Return ChildRelatedContent objects filtered by the related_content_id column
 * @method     array findByCreatedAt(string $created_at) Return ChildRelatedContent objects filtered by the created_at column
 * @method     array findByUpdatedAt(string $updated_at) Return ChildRelatedContent objects filtered by the updated_at column
 *
 */
abstract class RelatedContentQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \RelatedContent\Model\Base\RelatedContentQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\RelatedContent\\Model\\RelatedContent', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildRelatedContentQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildRelatedContentQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \RelatedContent\Model\RelatedContentQuery) {
            return $criteria;
        }
        $query = new \RelatedContent\Model\RelatedContentQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array[$content_id, $related_content_id] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildRelatedContent|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = RelatedContentTableMap::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(RelatedContentTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return   ChildRelatedContent A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT CONTENT_ID, RELATED_CONTENT_ID, CREATED_AT, UPDATED_AT FROM related_content WHERE CONTENT_ID = :p0 AND RELATED_CONTENT_ID = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildRelatedContent();
            $obj->hydrate($row);
            RelatedContentTableMap::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildRelatedContent|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ChildRelatedContentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(RelatedContentTableMap::CONTENT_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(RelatedContentTableMap::RELATED_CONTENT_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildRelatedContentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(RelatedContentTableMap::CONTENT_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(RelatedContentTableMap::RELATED_CONTENT_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the content_id column
     *
     * Example usage:
     * <code>
     * $query->filterByContentId(1234); // WHERE content_id = 1234
     * $query->filterByContentId(array(12, 34)); // WHERE content_id IN (12, 34)
     * $query->filterByContentId(array('min' => 12)); // WHERE content_id > 12
     * </code>
     *
     * @see       filterByContentRelatedByContentId()
     *
     * @param     mixed $contentId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildRelatedContentQuery The current query, for fluid interface
     */
    public function filterByContentId($contentId = null, $comparison = null)
    {
        if (is_array($contentId)) {
            $useMinMax = false;
            if (isset($contentId['min'])) {
                $this->addUsingAlias(RelatedContentTableMap::CONTENT_ID, $contentId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($contentId['max'])) {
                $this->addUsingAlias(RelatedContentTableMap::CONTENT_ID, $contentId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RelatedContentTableMap::CONTENT_ID, $contentId, $comparison);
    }

    /**
     * Filter the query on the related_content_id column
     *
     * Example usage:
     * <code>
     * $query->filterByRelatedContentId(1234); // WHERE related_content_id = 1234
     * $query->filterByRelatedContentId(array(12, 34)); // WHERE related_content_id IN (12, 34)
     * $query->filterByRelatedContentId(array('min' => 12)); // WHERE related_content_id > 12
     * </code>
     *
     * @see       filterByContentRelatedByRelatedContentId()
     *
     * @param     mixed $relatedContentId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildRelatedContentQuery The current query, for fluid interface
     */
    public function filterByRelatedContentId($relatedContentId = null, $comparison = null)
    {
        if (is_array($relatedContentId)) {
            $useMinMax = false;
            if (isset($relatedContentId['min'])) {
                $this->addUsingAlias(RelatedContentTableMap::RELATED_CONTENT_ID, $relatedContentId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($relatedContentId['max'])) {
                $this->addUsingAlias(RelatedContentTableMap::RELATED_CONTENT_ID, $relatedContentId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RelatedContentTableMap::RELATED_CONTENT_ID, $relatedContentId, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildRelatedContentQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(RelatedContentTableMap::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(RelatedContentTableMap::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RelatedContentTableMap::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildRelatedContentQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(RelatedContentTableMap::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(RelatedContentTableMap::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(RelatedContentTableMap::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \RelatedContent\Model\Thelia\Model\Content object
     *
     * @param \RelatedContent\Model\Thelia\Model\Content|ObjectCollection $content The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildRelatedContentQuery The current query, for fluid interface
     */
    public function filterByContentRelatedByContentId($content, $comparison = null)
    {
        if ($content instanceof \RelatedContent\Model\Thelia\Model\Content) {
            return $this
                ->addUsingAlias(RelatedContentTableMap::CONTENT_ID, $content->getId(), $comparison);
        } elseif ($content instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(RelatedContentTableMap::CONTENT_ID, $content->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByContentRelatedByContentId() only accepts arguments of type \RelatedContent\Model\Thelia\Model\Content or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ContentRelatedByContentId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildRelatedContentQuery The current query, for fluid interface
     */
    public function joinContentRelatedByContentId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ContentRelatedByContentId');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'ContentRelatedByContentId');
        }

        return $this;
    }

    /**
     * Use the ContentRelatedByContentId relation Content object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \RelatedContent\Model\Thelia\Model\ContentQuery A secondary query class using the current class as primary query
     */
    public function useContentRelatedByContentIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinContentRelatedByContentId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ContentRelatedByContentId', '\RelatedContent\Model\Thelia\Model\ContentQuery');
    }

    /**
     * Filter the query by a related \RelatedContent\Model\Thelia\Model\Content object
     *
     * @param \RelatedContent\Model\Thelia\Model\Content|ObjectCollection $content The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildRelatedContentQuery The current query, for fluid interface
     */
    public function filterByContentRelatedByRelatedContentId($content, $comparison = null)
    {
        if ($content instanceof \RelatedContent\Model\Thelia\Model\Content) {
            return $this
                ->addUsingAlias(RelatedContentTableMap::RELATED_CONTENT_ID, $content->getId(), $comparison);
        } elseif ($content instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(RelatedContentTableMap::RELATED_CONTENT_ID, $content->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByContentRelatedByRelatedContentId() only accepts arguments of type \RelatedContent\Model\Thelia\Model\Content or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ContentRelatedByRelatedContentId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return ChildRelatedContentQuery The current query, for fluid interface
     */
    public function joinContentRelatedByRelatedContentId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ContentRelatedByRelatedContentId');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'ContentRelatedByRelatedContentId');
        }

        return $this;
    }

    /**
     * Use the ContentRelatedByRelatedContentId relation Content object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \RelatedContent\Model\Thelia\Model\ContentQuery A secondary query class using the current class as primary query
     */
    public function useContentRelatedByRelatedContentIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinContentRelatedByRelatedContentId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ContentRelatedByRelatedContentId', '\RelatedContent\Model\Thelia\Model\ContentQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildRelatedContent $relatedContent Object to remove from the list of results
     *
     * @return ChildRelatedContentQuery The current query, for fluid interface
     */
    public function prune($relatedContent = null)
    {
        if ($relatedContent) {
            $this->addCond('pruneCond0', $this->getAliasedColName(RelatedContentTableMap::CONTENT_ID), $relatedContent->getContentId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(RelatedContentTableMap::RELATED_CONTENT_ID), $relatedContent->getRelatedContentId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the related_content table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(RelatedContentTableMap::DATABASE_NAME);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            RelatedContentTableMap::clearInstancePool();
            RelatedContentTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildRelatedContent or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildRelatedContent object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public function delete(ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(RelatedContentTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(RelatedContentTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        RelatedContentTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            RelatedContentTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     ChildRelatedContentQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(RelatedContentTableMap::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     ChildRelatedContentQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(RelatedContentTableMap::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     ChildRelatedContentQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(RelatedContentTableMap::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     ChildRelatedContentQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(RelatedContentTableMap::UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     ChildRelatedContentQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(RelatedContentTableMap::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     ChildRelatedContentQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(RelatedContentTableMap::CREATED_AT);
    }

} // RelatedContentQuery
