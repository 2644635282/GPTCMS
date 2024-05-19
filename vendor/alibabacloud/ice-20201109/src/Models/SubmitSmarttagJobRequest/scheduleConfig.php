<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\ICE\V20201109\Models\SubmitSmarttagJobRequest;

use AlibabaCloud\Tea\Model;

class scheduleConfig extends Model
{
    /**
     * @example acdbfe4323bcfdae
     *
     * @var string
     */
    public $pipelineId;

    /**
     * @example 4
     *
     * @var string
     */
    public $priority;
    protected $_name = [
        'pipelineId' => 'PipelineId',
        'priority'   => 'Priority',
    ];

    public function validate()
    {
    }

    public function toMap()
    {
        $res = [];
        if (null !== $this->pipelineId) {
            $res['PipelineId'] = $this->pipelineId;
        }
        if (null !== $this->priority) {
            $res['Priority'] = $this->priority;
        }

        return $res;
    }

    /**
     * @param array $map
     *
     * @return scheduleConfig
     */
    public static function fromMap($map = [])
    {
        $model = new self();
        if (isset($map['PipelineId'])) {
            $model->pipelineId = $map['PipelineId'];
        }
        if (isset($map['Priority'])) {
            $model->priority = $map['Priority'];
        }

        return $model;
    }
}
