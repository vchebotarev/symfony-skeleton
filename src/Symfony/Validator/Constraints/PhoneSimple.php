<?php

namespace App\Symfony\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class PhoneSimple extends Constraint
{
    /**
     * @var string
     */
    public $message = 'This is not valid phone number';

    /**
     * https://github.com/andr-04/inputmask-multi/blob/master/data/phone-codes.json
     * @var array
     */
    public $mask = [
        '1##########', '20##########', '211#########', '212#########', '213#########', '216########', '218########',
        '21821#######', '220#######', '221#########', '222########', '223########', '224########', '225########',
        '226########', '227########', '228########', '229########', '230#######', '231########', '232########',
        '233#########', '234##########', '234##########', '234#######', '234########', '235########', '236########',
        '237########', '238#######', '239#######', '240#########', '241#######', '242#########', '243#########',
        '244#########', '245#######', '246#######', '247####', '248#######', '249#########', '250#########',
        '251#########', '252########', '252#######', '252#######', '253########', '254#########', '255#########',
        '256#########', '257########', '258########', '260#########', '261#########', '262#########', '262#########',
        '263#######', '264#########', '265#########', '2651######', '266########', '267########', '268########',
        '269#######', '27#########', '290####', '290####', '291#######', '297#######', '298######', '299######',
        '30##########', '31#########', '32#########', '33#########', '34#########', '350########', '351#########',
        '352#########', '353#########', '354#######', '355#########', '356########', '357########', '358##########',
        '359#########', '36#########', '370########', '371########', '372########', '372#######', '373########',
        '374########', '375#########', '376######', '377#########', '377########', '378##########', '380#########',
        '381#########', '382########', '385########', '386########', '387######', '387#######', '389########',
        '39##########', '396698#####', '40#########', '41#########', '420#########', '421#########', '423##########',
        '43##########', '44##########', '45########', '46#########', '47########', '48#########', '49###########',
        '49##########', '49#######', '49########', '49#########', '49######', '500#####', '501#######', '502########',
        '503########', '504########', '505########', '506########', '507#######', '508######', '509########',
        '51#########', '52##########', '52########', '53########', '54##########', '55##########', '55##7#######',
        '55##9########', '56#########', '57##########', '58##########', '590#########', '591########', '592#######',
        '593#########', '593########', '594#########', '595#########', '596#########', '597######', '597#######',
        '598########', '599#######', '599#######', '599#######', '5999#######', '60#########', '60########',
        '60#########', '60#######', '61#########', '628########', '628#########', '628##########', '62#######',
        '62########', '62#########', '63##########', '64#########', '64##########', '64########', '65########',
        '66########', '66#########', '670#######', '67077######', '67078######', '6721#####', '6723#####',
        '673#######', '674#######', '675########', '676#####', '677#####', '677#######', '678#####', '678#######',
        '679#######', '680#######', '681######', '682#####', '683####', '685######', '686#####', '687######',
        '6882####', '68890####', '689######', '690####', '691#######', '692#######', '7##########', '81#########',
        '81##########', '82#########', '84##########', '84#########', '850########', '850#################',
        '850######', '850##########', '850########', '850191#######', '852########', '853########', '855########',
        '85620########', '856########', '86##########', '86###########', '86############', '880########', '886########',
        '886#########', '90##########', '91##########', '92##########', '93#########', '94#########', '95######',
        '95########', '95#######', '960#######', '961########', '961#######', '962#########', '963#########',
        '964##########', '965########', '966########', '9665########', '967#########', '967########', '967#######',
        '968########', '970#########', '971########', '9715########', '972########', '9725########', '973########',
        '974########', '975#######', '97517######', '976########', '977########', '98##########', '992#########',
        '993########', '994#########', '995#########', '996#########', '998#########',
    ];
}