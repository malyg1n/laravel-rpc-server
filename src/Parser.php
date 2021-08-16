<?php

namespace Malyg1n\JsonRpcServer;

use Illuminate\Support\Collection;
use Psy\Exception\ParseErrorException;
use Malyg1n\JsonRpcServer\Http\Request;
use Illuminate\Support\Facades\Validator;
use Malyg1n\JsonRpcServer\Exceptions\InvalidParams;
use Malyg1n\JsonRpcServer\Exceptions\InvalidRequestException;

class Parser implements ParserInterface
{
    /**
     * @var bool
     */
    private bool $has_error = false;

    /**
     * @var string
     */
    private string $content;

    /**
     * @var Collection
     */
    private Collection $decoded_content;

    /**
     * @param string $content
     */
    public function __construct(string $content = '')
    {
        $this->content = $content;

        try {
            $decoded_content       = \json_decode($this->content, true, 512, \JSON_THROW_ON_ERROR);
            $this->decoded_content = new Collection($decoded_content);
        } catch (\Throwable $e) {
            $this->decoded_content = new Collection();
            $this->has_error       = true;
        }
    }

    /**
     * @return array[]|InvalidParams[]|InvalidRequestException[]|ParseErrorException[]
     */
    public function getRequest(): array
    {
        $result = $this->validation($this->getContent()->toArray());

        return [\is_array($result) ? Request::loadArray($result) : $result];
    }

    /**
     * @param array $content
     *
     * @return array|InvalidParams|InvalidRequestException|ParseErrorException
     */
    private function validation(array $content = [])
    {
        if ($this->hasError()) {
            return new ParseErrorException();
        }

        if (! \is_array($content)) {
            return new InvalidRequestException();
        }

        $validation = Validator::make($content, self::rules());

        return $validation->fails()
            ? new InvalidParams($validation->errors()->toArray())
            : $content;
    }

    /**
     * @return bool
     */
    private function hasError(): bool
    {
        return $this->has_error;
    }

    /**
     * @return Collection
     */
    private function getContent(): Collection
    {
        return $this->decoded_content;
    }

    /**
     * @return string[]
     */
    private static function rules(): array
    {
        return [
            'jsonrpc' => 'required|string',
            'method'  => 'required|string',
            'params'  => 'array',
            'id'      => 'required|regex:/[0-9a-zA-Z]+/',
        ];
    }
}
