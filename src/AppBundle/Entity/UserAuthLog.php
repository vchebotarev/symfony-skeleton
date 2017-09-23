<?php

namespace AppBundle\Entity;

use App\Doctrine\Column;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user_auth_log")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserAuthLogRepository")
 * @ORM\HasLifecycleCallbacks
 */
class UserAuthLog
{
    const TYPE_UNKNOWN           = 0;
    const TYPE_USERNAME_PASSWORD = 1;
    const TYPE_REMEMBER_ME       = 2;
    const TYPE_OAUTH             = 3;
    const TYPE_LINK              = 4;

    use Column\Id;

    use Column\Type;

    use Column\User;

    use Column\Visitor;

    use Column\UserAgent;

    use Column\Ip;

    use Column\DateCreated;

    //todo продолжительность в секундах или минутах (или до окончания сессии или до последнего запроса)

}
