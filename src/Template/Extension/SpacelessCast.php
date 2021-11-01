<?php

namespace Moo\Template\Extension;

use Moo\Template\Parser\Spaceless;
use SilverStripe\Core\Extension;
use SilverStripe\ORM\FieldType\DBField;

class SpacelessCast extends Extension
{
    private static array $casting = [
        'Spaceless' => 'HTMLText',
    ];

    /**
     * Get string without extra spaces.
     */
    public function Spaceless(): DBField
    {
        return DBField::create_field(
            'HTMLText',
            Spaceless::spaceless([
                'Template' => [
                    'php' => $this->getOwner()->RAW(),
                ],
            ])
        );
    }
}
