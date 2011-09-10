<?php
namespace Tatami\Models\Repositories;
class PasswordRecoveryTokenRepository extends EntityRepository
{
    public function getNewerTokens($userId, $dateTime)
    {
	return $this->createQueryBuilder('token')
		->where('token.user = :userId and token.created > :created')
		->setParameters(array('userId' => $userId, 'created' => $dateTime))
		->getQuery()
		->getResult()
	    ;
    }
}