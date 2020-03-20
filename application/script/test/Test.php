<?php

namespace app\script\test;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;
use think\Db;

class Test extends Command
{
    private $b_prefix = 'dxz_b';

    /**
     * 面板
     */
    protected function configure()
    {
        $this->setName('test')->setDescription('test');
        $this->addOption('t', 't', Argument::OPTIONAL, '');
        $this->addArgument('cmd');
    }

    /**
     * 入口
     * @param Input $input
     * @param Output $output
     */
    protected function execute(Input $input, Output $output)
    {
        ini_set('memory_limit', '3048M');
        $time = time();
        $this->output->writeln(date('Y-m-d H:i:s'));
        $this->handle();
        $time = time() - $time;
        $this->output->writeln("all steps end!" . round($time / 60) . ':' . round($time / 60));
    }

    private function handle()
    {
        $d = date('Y-m-d H:i:s');
        $f = date('Y-m-d H:i:s',strtotime($d.' +1month'));
        echo $d,PHP_EOL,$f;
    }
}