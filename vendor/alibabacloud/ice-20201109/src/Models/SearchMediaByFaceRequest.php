<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\ICE\V20201109\Models;

use AlibabaCloud\Tea\Model;

class SearchMediaByFaceRequest extends Model
{
    /**
     * @example 2d3bf1e35a1e42b5ab338d701efa****
     *
     * @var string
     */
    public $entityId;

    /**
     * @example zxtest-huangxuan-2023-3-7-V1
     *
     * @var string
     */
    public $faceSearchToken;

    /**
     * @example 1
     *
     * @var int
     */
    public $pageNo;

    /**
     * @example 10
     *
     * @var int
     */
    public $pageSize;

    /**
     * @example https://****.oss-cn-shanghai.aliyuncs.com/input/huangxuan****.jpg
     *
     * @var string
     */
    public $personImageUrl;
    protected $_name = [
        'entityId'        => 'EntityId',
        'faceSearchToken' => 'FaceSearchToken',
        'pageNo'          => 'PageNo',
        'pageSize'        => 'PageSize',
        'personImageUrl'  => 'PersonImageUrl',
    ];

    public function validate()
    {
    }

    public function toMap()
    {
        $res = [];
        if (null !== $this->entityId) {
            $res['EntityId'] = $this->entityId;
        }
        if (null !== $this->faceSearchToken) {
            $res['FaceSearchToken'] = $this->faceSearchToken;
        }
        if (null !== $this->pageNo) {
            $res['PageNo'] = $this->pageNo;
        }
        if (null !== $this->pageSize) {
            $res['PageSize'] = $this->pageSize;
        }
        if (null !== $this->personImageUrl) {
            $res['PersonImageUrl'] = $this->personImageUrl;
        }

        return $res;
    }

    /**
     * @param array $map
     *
     * @return SearchMediaByFaceRequest
     */
    public static function fromMap($map = [])
    {
        $model = new self();
        if (isset($map['EntityId'])) {
            $model->entityId = $map['EntityId'];
        }
        if (isset($map['FaceSearchToken'])) {
            $model->faceSearchToken = $map['FaceSearchToken'];
        }
        if (isset($map['PageNo'])) {
            $model->pageNo = $map['PageNo'];
        }
        if (isset($map['PageSize'])) {
            $model->pageSize = $map['PageSize'];
        }
        if (isset($map['PersonImageUrl'])) {
            $model->personImageUrl = $map['PersonImageUrl'];
        }

        return $model;
    }
}
