<?php

namespace Tests;

use Filesystem;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Prophecy\Argument;
use Utils;

class UtilsTest extends MockeryTestCase
{
    /**
     * PHPUnitMock
     * + Optional expectations definition. No need to define expectations for all used methods.
     * - 1 expectation/stubbing definition for ALL usages.
     *   Enforces defining the expectation for all the usages of the method (and in the right execution order).
     *   More info:
     *      two "expect" calls that only differ in the arguments passed to with will fail because both will match
     *      but only one will verify as having the expected behavior
     *      https://stackoverflow.com/a/5989095
     * - No spies.
     * - No partials. (a.k.a. default behaviour == return null)
     *
     * Prophecy
     * + Spies. (But only when not stubbed at all!)
     * + 1 expectation/stubbing definition per usage.
     * - Mandatory expectations definition. Enforces defining ALL the usages of ALL the used methods (either with stubbing or expectations), once
     *   a first stubbing/expectation has been defined.
     * - Once stubbed, can't be a Spy. (because of the above)
     * - No partials. (a.k.a. default behaviour == return null)
     *
     * Mockery
     * + Partials. ((a.k.a. default behaviour == return original))
     * + Spies.
     * + Allows Stub + Spy. <- super cool
     * + 1 expectation/stubbing definition per usage.
     * + Optional expectations definition. (with shouldIgnoreMissing()) No need to define expectations for all usages of all methods.
     */

    public function testMockery()
    {
        $filesystem = Mockery::mock(Filesystem::class)->makePartial();
        $filesystem->shouldIgnoreMissing();

        $filesystem->allows()->isDir(Mockery::any())->andReturns(false); // stubs
        // $filesystem->expects()->createFile('logs/log_1.json'); // expects

        $utils = new Utils($filesystem);

        $utils->log('asdf');

        // $filesystem->shouldHaveReceived('createFile')->with('logs/'); // spied (optional)
        $filesystem->shouldHaveReceived('createFile')->with('logs/log_1.json'); // spied
        // $filesystem->shouldHaveReceived('write')->with('logs/log_1.json', 'asdf'); // spied (optional)
    }

    public function testPhpunit()
    {
        $filesystem = $this->createMock(Filesystem::class);

        $filesystem->method('isDir')->willReturn(false); // stubs

        // $filesystem->expects($this->once())->method('createFile')->with('logs/log_1.json'); // expects
        // ^ fails if the same method happens to be called more times with different arguments :(
        // v alternative: need to specify every call

        // expects
        $filesystem
            ->expects($this->exactly(2))
            ->method('createFile')
            ->withConsecutive(
                ['logs/'],
                ['logs/log_1.json']
            );

        // at least we are not forced to "expect" the write() call, like with prophecy

        $utils = new Utils($filesystem);

        $utils->log('asdf');
    }

    public function testProphecy()
    {
        $filesystem = $this->prophesize(Filesystem::class);

        $filesystem->isDir(Argument::any())->willReturn(false); // stubs
        // $filesystem->createFile('logs/')->shouldBeCalled();
        $filesystem->createFile('logs/log_1.json')->shouldBeCalled(); // expects
        // $filesystem->write('logs/log_1.json', 'asdf')->shouldBeCalled();
        
        $utils = new Utils($filesystem->reveal());

        $utils->log('asdf');

        // $filesystem->createFile('logs/')->shouldHaveBeenCalled();
        // $filesystem->createFile('logs/log_1.json')->shouldHaveBeenCalled();
        // $filesystem->write('logs/log_1.json', 'asdf')->shouldHaveBeenCalled();
    }
}