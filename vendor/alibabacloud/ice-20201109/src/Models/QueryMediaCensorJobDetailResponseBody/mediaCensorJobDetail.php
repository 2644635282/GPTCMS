<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\ICE\V20201109\Models\QueryMediaCensorJobDetailResponseBody;

use AlibabaCloud\SDK\ICE\V20201109\Models\QueryMediaCensorJobDetailResponseBody\mediaCensorJobDetail\barrageCensorResult;
use AlibabaCloud\SDK\ICE\V20201109\Models\QueryMediaCensorJobDetailResponseBody\mediaCensorJobDetail\coverImageCensorResults;
use AlibabaCloud\SDK\ICE\V20201109\Models\QueryMediaCensorJobDetailResponseBody\mediaCensorJobDetail\descCensorResult;
use AlibabaCloud\SDK\ICE\V20201109\Models\QueryMediaCensorJobDetailResponseBody\mediaCensorJobDetail\input;
use AlibabaCloud\SDK\ICE\V20201109\Models\QueryMediaCensorJobDetailResponseBody\mediaCensorJobDetail\titleCensorResult;
use AlibabaCloud\SDK\ICE\V20201109\Models\QueryMediaCensorJobDetailResponseBody\mediaCensorJobDetail\vensorCensorResult;
use AlibabaCloud\SDK\ICE\V20201109\Models\QueryMediaCensorJobDetailResponseBody\mediaCensorJobDetail\videoCensorConfig;
use AlibabaCloud\Tea\Model;

class mediaCensorJobDetail extends Model
{
    /**
     * @var barrageCensorResult
     */
    public $barrageCensorResult;

    /**
     * @example InvalidParameter.ResourceNotFound
     *
     * @var string
     */
    public $code;

    /**
     * @var coverImageCensorResults
     */
    public $coverImageCensorResults;

    /**
     * @example 2018-09-13T16:32:24Z
     *
     * @var string
     */
    public $creationTime;

    /**
     * @var descCensorResult
     */
    public $descCensorResult;

    /**
     * @example 2018-09-13T16:38:24Z
     *
     * @var string
     */
    public $finishTime;

    /**
     * @var input
     */
    public $input;

    /**
     * @example f8f166eea7a44e9bb0a4aecf9543****
     *
     * @var string
     */
    public $jobId;

    /**
     * @example The resource operated cannot be found
     *
     * @var string
     */
    public $message;

    /**
     * @example c5b30b7c0d0e4a0abde1d5f9e751****
     *
     * @var string
     */
    public $pipelineId;

    /**
     * @example Success
     *
     * @var string
     */
    public $state;

    /**
     * @example block
     *
     * @var string
     */
    public $suggestion;

    /**
     * @var titleCensorResult
     */
    public $titleCensorResult;

    /**
     * @example example userdata ****
     *
     * @var string
     */
    public $userData;

    /**
     * @var vensorCensorResult
     */
    public $vensorCensorResult;

    /**
     * @var videoCensorConfig
     */
    public $videoCensorConfig;
    protected $_name = [
        'barrageCensorResult'     => 'BarrageCensorResult',
        'code'                    => 'Code',
        'coverImageCensorResults' => 'CoverImageCensorResults',
        'creationTime'            => 'CreationTime',
        'descCensorResult'        => 'DescCensorResult',
        'finishTime'              => 'FinishTime',
        'input'                   => 'Input',
        'jobId'                   => 'JobId',
        'message'                 => 'Message',
        'pipelineId'              => 'PipelineId',
        'state'                   => 'State',
        'suggestion'              => 'Suggestion',
        'titleCensorResult'       => 'TitleCensorResult',
        'userData'                => 'UserData',
        'vensorCensorResult'      => 'VensorCensorResult',
        'videoCensorConfig'       => 'VideoCensorConfig',
    ];

    public function validate()
    {
    }

    public function toMap()
    {
        $res = [];
        if (null !== $this->barrageCensorResult) {
            $res['BarrageCensorResult'] = null !== $this->barrageCensorResult ? $this->barrageCensorResult->toMap() : null;
        }
        if (null !== $this->code) {
            $res['Code'] = $this->code;
        }
        if (null !== $this->coverImageCensorResults) {
            $res['CoverImageCensorResults'] = null !== $this->coverImageCensorResults ? $this->coverImageCensorResults->toMap() : null;
        }
        if (null !== $this->creationTime) {
            $res['CreationTime'] = $this->creationTime;
        }
        if (null !== $this->descCensorResult) {
            $res['DescCensorResult'] = null !== $this->descCensorResult ? $this->descCensorResult->toMap() : null;
        }
        if (null !== $this->finishTime) {
            $res['FinishTime'] = $this->finishTime;
        }
        if (null !== $this->input) {
            $res['Input'] = null !== $this->input ? $this->input->toMap() : null;
        }
        if (null !== $this->jobId) {
            $res['JobId'] = $this->jobId;
        }
        if (null !== $this->message) {
            $res['Message'] = $this->message;
        }
        if (null !== $this->pipelineId) {
            $res['PipelineId'] = $this->pipelineId;
        }
        if (null !== $this->state) {
            $res['State'] = $this->state;
        }
        if (null !== $this->suggestion) {
            $res['Suggestion'] = $this->suggestion;
        }
        if (null !== $this->titleCensorResult) {
            $res['TitleCensorResult'] = null !== $this->titleCensorResult ? $this->titleCensorResult->toMap() : null;
        }
        if (null !== $this->userData) {
            $res['UserData'] = $this->userData;
        }
        if (null !== $this->vensorCensorResult) {
            $res['VensorCensorResult'] = null !== $this->vensorCensorResult ? $this->vensorCensorResult->toMap() : null;
        }
        if (null !== $this->videoCensorConfig) {
            $res['VideoCensorConfig'] = null !== $this->videoCensorConfig ? $this->videoCensorConfig->toMap() : null;
        }

        return $res;
    }

    /**
     * @param array $map
     *
     * @return mediaCensorJobDetail
     */
    public static function fromMap($map = [])
    {
        $model = new self();
        if (isset($map['BarrageCensorResult'])) {
            $model->barrageCensorResult = barrageCensorResult::fromMap($map['BarrageCensorResult']);
        }
        if (isset($map['Code'])) {
            $model->code = $map['Code'];
        }
        if (isset($map['CoverImageCensorResults'])) {
            $model->coverImageCensorResults = coverImageCensorResults::fromMap($map['CoverImageCensorResults']);
        }
        if (isset($map['CreationTime'])) {
            $model->creationTime = $map['CreationTime'];
        }
        if (isset($map['DescCensorResult'])) {
            $model->descCensorResult = descCensorResult::fromMap($map['DescCensorResult']);
        }
        if (isset($map['FinishTime'])) {
            $model->finishTime = $map['FinishTime'];
        }
        if (isset($map['Input'])) {
            $model->input = input::fromMap($map['Input']);
        }
        if (isset($map['JobId'])) {
            $model->jobId = $map['JobId'];
        }
        if (isset($map['Message'])) {
            $model->message = $map['Message'];
        }
        if (isset($map['PipelineId'])) {
            $model->pipelineId = $map['PipelineId'];
        }
        if (isset($map['State'])) {
            $model->state = $map['State'];
        }
        if (isset($map['Suggestion'])) {
            $model->suggestion = $map['Suggestion'];
        }
        if (isset($map['TitleCensorResult'])) {
            $model->titleCensorResult = titleCensorResult::fromMap($map['TitleCensorResult']);
        }
        if (isset($map['UserData'])) {
            $model->userData = $map['UserData'];
        }
        if (isset($map['VensorCensorResult'])) {
            $model->vensorCensorResult = vensorCensorResult::fromMap($map['VensorCensorResult']);
        }
        if (isset($map['VideoCensorConfig'])) {
            $model->videoCensorConfig = videoCensorConfig::fromMap($map['VideoCensorConfig']);
        }

        return $model;
    }
}
