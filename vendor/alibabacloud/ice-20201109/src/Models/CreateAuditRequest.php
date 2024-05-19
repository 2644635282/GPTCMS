<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\ICE\V20201109\Models;

use AlibabaCloud\Tea\Model;

class CreateAuditRequest extends Model
{
    /**
     * @var string
     */
    public $appId;

    /**
     * @var string
     */
    public $auditContent;
    protected $_name = [
        'appId'        => 'AppId',
        'auditContent' => 'AuditContent',
    ];

    public function validate()
    {
    }

    public function toMap()
    {
        $res = [];
        if (null !== $this->appId) {
            $res['AppId'] = $this->appId;
        }
        if (null !== $this->auditContent) {
            $res['AuditContent'] = $this->auditContent;
        }

        return $res;
    }

    /**
     * @param array $map
     *
     * @return CreateAuditRequest
     */
    public static function fromMap($map = [])
    {
        $model = new self();
        if (isset($map['AppId'])) {
            $model->appId = $map['AppId'];
        }
        if (isset($map['AuditContent'])) {
            $model->auditContent = $map['AuditContent'];
        }

        return $model;
    }
}
