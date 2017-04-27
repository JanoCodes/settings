<?php
/**
 * Jano Ticketing System
 * Copyright (C) 2016-2017 Andrew Ying
 *
 * This file is part of Jano Ticketing System.
 *
 * Jano Ticketing System is free software: you can redistribute it and/or
 * modify it under the terms of the GNU General Public License v3.0 as
 * published by the Free Software Foundation.
 *
 * Jano Ticketing System is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Jano\Settings;

use HJSON\HJSONParser;
use HJSON\HJSONStringifier;
use Illuminate\Filesystem\Filesystem;

class HjsonSettingStore extends SettingStore
{
    /**
     * The Filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    private $files;

    /**
     * The HJSONParser instance.
     *
     * @var \HJSON\HJSONParser
     */
    private $parser;

    /**
     * The path of the settings file.
     *
     * @var string
     */
    private $path;

    /**
     * The HJSONStringifier instance.
     *
     * @var \HJSON\HJSONStringifier
     */
    private $stringifier;

    /**
     * Construct the class method.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     * @param \HJSON\HJSONParser $parser
     * @param \HJSON\HJSONStringifier $stringifier
     * @param string $path
     * @throws \InvalidArgumentException
     */
    public function __construct(Filesystem $files, HJSONParser $parser, HJSONStringifier $stringifier, $path = null)
    {
        $this->files = $files;
        $this->parser = $parser;
        $this->stringifier = $stringifier;
        $this->setPath($path ?: storage_path() . '/settings.hjson');
    }

    /**
     * Set the path for the JSON file.
     *
     * @param string $path
     * @throws \InvalidArgumentException
     */
    public function setPath($path)
    {
        if (!$this->files->exists($path)) {
            $result = $this->files->put($path, '{}');
            if ($result === false) {
                throw new \InvalidArgumentException("Could not write to $path.");
            }
        }

        if (!$this->files->isWritable($path)) {
            throw new \InvalidArgumentException("$path is not writable.");
        }

        $this->path = $path;
    }

    /**
     * {@inheritdoc}
     * @throws \RuntimeException
     */
    protected function read()
    {
        $contents = $this->files->get($this->path);

        $data = $this->parser->parseWsc($contents);

        if ($data === null) {
            throw new \RuntimeException("Invalid HJSON syntax in {$this->path}");
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    protected function write(array $data)
    {
        if ($data) {
            $contents = $this->stringifier->stringifyWsc($data);
        }
        else {
            $contents = '{}';
        }

        $this->files->put($this->path, $contents);
    }
}
