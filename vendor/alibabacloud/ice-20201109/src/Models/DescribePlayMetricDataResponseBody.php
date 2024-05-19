<?php

// This file is auto-generated, don't edit it. Thanks.

namespace AlibabaCloud\SDK\ICE\V20201109\Models;

use AlibabaCloud\SDK\ICE\V20201109\Models\DescribePlayMetricDataResponseBody\nodes;
use AlibabaCloud\Tea\Model;

class DescribePlayMetricDataResponseBody extends Model
{
    /**
     * @var nodes[]
     */
    public $nodes;

    /**
     * @var string
     */
    public $requestId;

    /**
     * @var string
     */
    public $summaryData;
    protected $_name = [
        'nodes'       => 'Nodes',
        'requestId'   => 'RequestId',
        'summaryData' => 'SummaryData',
    ];

    public function validate()
    {
    }

    public function toMap()
    {
        $res = [];
        if (null !== $this->nodes) {
            $res['Nodes'] = [];
            if (null !== $this->nodes && \is_array($this->nodes)) {
                $n = 0;
                foreach ($this->nodes as $item) {
                    $res['Nodes'][$n++] = null !== $item ? $item->toMap() : $item;
                }
            }
        }
        if (null !== $this->requestId) {
            $res['RequestId'] = $this->requestId;
        }
        if (null !== $this->summaryData) {
            $res['SummaryData'] = $this->summaryData;
        }

        return $res;
    }

    /**
     * @param array $map
     *
     * @return DescribePlayMetricDataResponseBody
     */
    public static function fromMap($map = [])
    {
        $model = new self();
        if (isset($map['Nodes'])) {
            if (!empty($map['Nodes'])) {
                $model->nodes = [];
                $n            = 0;
                foreach ($map['Nodes'] as $item) {
                    $model->nodes[$n++] = null !== $item ? nodes::fromMap($item) : $item;
                }
            }
        }
        if (isset($map['RequestId'])) {
            $model->requestId = $map['RequestId'];
        }
        if (isset($map['SummaryData'])) {
            $model->summaryData = $map['SummaryData'];
        }

        return $model;
    }
}
