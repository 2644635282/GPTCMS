<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\ICE\V20201109\Models\GetTranscodeJobResponseBody;

use AlibabaCloud\SDK\ICE\V20201109\Models\GetTranscodeJobResponseBody\transcodeParentJob\inputGroup;
use AlibabaCloud\SDK\ICE\V20201109\Models\GetTranscodeJobResponseBody\transcodeParentJob\outputGroup;
use AlibabaCloud\SDK\ICE\V20201109\Models\GetTranscodeJobResponseBody\transcodeParentJob\scheduleConfig;
use AlibabaCloud\SDK\ICE\V20201109\Models\GetTranscodeJobResponseBody\transcodeParentJob\transcodeJobList;
use AlibabaCloud\Tea\Model;

class transcodeParentJob extends Model
{
    /**
     * @example 2022-01-12T08:49:41Z
     *
     * @var string
     */
    public $createTime;

    /**
     * @example 2022-01-12T08:49:41Z
     *
     * @var string
     */
    public $finishTime;

    /**
     * @var inputGroup[]
     */
    public $inputGroup;

    /**
     * @example 1
     *
     * @var int
     */
    public $jobCount;

    /**
     * @example transcode-job
     *
     * @var string
     */
    public $name;

    /**
     * @var outputGroup[]
     */
    public $outputGroup;

    /**
     * @example 8b2198504dd340b7b3c9842a74fc9baa
     *
     * @var string
     */
    public $parentJobId;

    /**
     * @example 0
     *
     * @var int
     */
    public $percent;

    /**
     * @example 31E30781-9495-5E2D-A84D-759B0A01E262
     *
     * @var string
     */
    public $requestId;

    /**
     * @var scheduleConfig
     */
    public $scheduleConfig;

    /**
     * @example Success
     *
     * @var string
     */
    public $status;

    /**
     * @example 2022-01-12T08:49:41Z
     *
     * @var string
     */
    public $submitTime;

    /**
     * @var transcodeJobList[]
     */
    public $transcodeJobList;

    /**
     * @example API
     *
     * @var string
     */
    public $triggerSource;

    /**
     * @example user-data
     *
     * @var string
     */
    public $userData;
    protected $_name = [
        'createTime'       => 'CreateTime',
        'finishTime'       => 'FinishTime',
        'inputGroup'       => 'InputGroup',
        'jobCount'         => 'JobCount',
        'name'             => 'Name',
        'outputGroup'      => 'OutputGroup',
        'parentJobId'      => 'ParentJobId',
        'percent'          => 'Percent',
        'requestId'        => 'RequestId',
        'scheduleConfig'   => 'ScheduleConfig',
        'status'           => 'Status',
        'submitTime'       => 'SubmitTime',
        'transcodeJobList' => 'TranscodeJobList',
        'triggerSource'    => 'TriggerSource',
        'userData'         => 'UserData',
    ];

    public function validate()
    {
    }

    public function toMap()
    {
        $res = [];
        if (null !== $this->createTime) {
            $res['CreateTime'] = $this->createTime;
        }
        if (null !== $this->finishTime) {
            $res['FinishTime'] = $this->finishTime;
        }
        if (null !== $this->inputGroup) {
            $res['InputGroup'] = [];
            if (null !== $this->inputGroup && \is_array($this->inputGroup)) {
                $n = 0;
                foreach ($this->inputGroup as $item) {
                    $res['InputGroup'][$n++] = null !== $item ? $item->toMap() : $item;
                }
            }
        }
        if (null !== $this->jobCount) {
            $res['JobCount'] = $this->jobCount;
        }
        if (null !== $this->name) {
            $res['Name'] = $this->name;
        }
        if (null !== $this->outputGroup) {
            $res['OutputGroup'] = [];
            if (null !== $this->outputGroup && \is_array($this->outputGroup)) {
                $n = 0;
                foreach ($this->outputGroup as $item) {
                    $res['OutputGroup'][$n++] = null !== $item ? $item->toMap() : $item;
                }
            }
        }
        if (null !== $this->parentJobId) {
            $res['ParentJobId'] = $this->parentJobId;
        }
        if (null !== $this->percent) {
            $res['Percent'] = $this->percent;
        }
        if (null !== $this->requestId) {
            $res['RequestId'] = $this->requestId;
        }
        if (null !== $this->scheduleConfig) {
            $res['ScheduleConfig'] = null !== $this->scheduleConfig ? $this->scheduleConfig->toMap() : null;
        }
        if (null !== $this->status) {
            $res['Status'] = $this->status;
        }
        if (null !== $this->submitTime) {
            $res['SubmitTime'] = $this->submitTime;
        }
        if (null !== $this->transcodeJobList) {
            $res['TranscodeJobList'] = [];
            if (null !== $this->transcodeJobList && \is_array($this->transcodeJobList)) {
                $n = 0;
                foreach ($this->transcodeJobList as $item) {
                    $res['TranscodeJobList'][$n++] = null !== $item ? $item->toMap() : $item;
                }
            }
        }
        if (null !== $this->triggerSource) {
            $res['TriggerSource'] = $this->triggerSource;
        }
        if (null !== $this->userData) {
            $res['UserData'] = $this->userData;
        }

        return $res;
    }

    /**
     * @param array $map
     *
     * @return transcodeParentJob
     */
    public static function fromMap($map = [])
    {
        $model = new self();
        if (isset($map['CreateTime'])) {
            $model->createTime = $map['CreateTime'];
        }
        if (isset($map['FinishTime'])) {
            $model->finishTime = $map['FinishTime'];
        }
        if (isset($map['InputGroup'])) {
            if (!empty($map['InputGroup'])) {
                $model->inputGroup = [];
                $n                 = 0;
                foreach ($map['InputGroup'] as $item) {
                    $model->inputGroup[$n++] = null !== $item ? inputGroup::fromMap($item) : $item;
                }
            }
        }
        if (isset($map['JobCount'])) {
            $model->jobCount = $map['JobCount'];
        }
        if (isset($map['Name'])) {
            $model->name = $map['Name'];
        }
        if (isset($map['OutputGroup'])) {
            if (!empty($map['OutputGroup'])) {
                $model->outputGroup = [];
                $n                  = 0;
                foreach ($map['OutputGroup'] as $item) {
                    $model->outputGroup[$n++] = null !== $item ? outputGroup::fromMap($item) : $item;
                }
            }
        }
        if (isset($map['ParentJobId'])) {
            $model->parentJobId = $map['ParentJobId'];
        }
        if (isset($map['Percent'])) {
            $model->percent = $map['Percent'];
        }
        if (isset($map['RequestId'])) {
            $model->requestId = $map['RequestId'];
        }
        if (isset($map['ScheduleConfig'])) {
            $model->scheduleConfig = scheduleConfig::fromMap($map['ScheduleConfig']);
        }
        if (isset($map['Status'])) {
            $model->status = $map['Status'];
        }
        if (isset($map['SubmitTime'])) {
            $model->submitTime = $map['SubmitTime'];
        }
        if (isset($map['TranscodeJobList'])) {
            if (!empty($map['TranscodeJobList'])) {
                $model->transcodeJobList = [];
                $n                       = 0;
                foreach ($map['TranscodeJobList'] as $item) {
                    $model->transcodeJobList[$n++] = null !== $item ? transcodeJobList::fromMap($item) : $item;
                }
            }
        }
        if (isset($map['TriggerSource'])) {
            $model->triggerSource = $map['TriggerSource'];
        }
        if (isset($map['UserData'])) {
            $model->userData = $map['UserData'];
        }

        return $model;
    }
}
