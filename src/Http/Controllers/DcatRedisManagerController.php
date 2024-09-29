<?php

namespace Juenfy\DcatRedisManager\Http\Controllers;

use Dcat\Admin\Layout\Content;
use Dcat\Admin\Admin;
use Juenfy\DcatRedisManager\RedisManager;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

class DcatRedisManagerController extends Controller
{
    /**
     * Index page.
     *
     * @return Content
     */
    public function index(Content $content)
    {
        $connection = request('conn', 'default');

        $manager = $this->manager();

        $variables = [
            'conn' => $connection,
            'info' => $manager->getInformation(),
            'connections' => $manager->getConnections(),
            'keys' => $manager->scan(
                request('pattern', '*'),
                request('count', 50)
            ),
        ];
        return $content->header('Redis manager')
            ->description('Connections')
            ->breadcrumb(['text' => 'Redis manager'])
            ->body(Admin::view('juenfy.dcat-redis-manager::index', $variables));
    }

    /**
     * Edit page.
     *
     * @param Request $request
     *
     * @return Content
     */
    public function edit(Content $content, Request $request)
    {
        $connection = $request->get('conn', 'default');

        $manager = $this->manager();

        $variables = [
            'conn' => $connection,
            'info' => $manager->getInformation(),
            'connections' => $manager->getConnections(),
            'data' => $manager->fetch($request->get('key')),
        ];

        if (empty($variables['data'])) {
            $view = 'juenfy.dcat-redis-manager::edit.nil';
        } else {
            $view = 'juenfy.dcat-redis-manager::edit.' . $variables['data']['type'];
        }
        return $content->header('Redis manager')
            ->description('Connections')
            ->breadcrumb(
                ['text' => 'Redis manager', 'url' => admin_route('redis-index', ['conn' => $connection])],
                ['text' => 'Edit']
            )
            ->body(Admin::view($view, $variables));
    }

    /**
     * Create page.
     *
     * @param Request $request
     *
     * @return Content
     */
    public function create(Content $content, Request $request)
    {
        $connection = $request->get('conn', 'default');

        $manager = $this->manager();

        $vars = [
            'conn' => $connection,
            'info' => $manager->getInformation(),
            'connections' => $manager->getConnections(),
            'type' => $request->get('type'),
        ];

        $view = 'juenfy.dcat-redis-manager::create.' . $vars['type'];

        return $content->header('Redis manager')
            ->description('Connections')
            ->breadcrumb(
                ['text' => 'Redis manager', 'url' => admin_route('redis-index', ['conn' => $connection])],
                ['text' => 'Create']
            )
            ->body(Admin::view($view, $vars));
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        $type = $request->get('type');

        return $this->manager()->{$type}()->store($request->all());
    }

    /**
     * @param Request $request
     *
     * @return int
     */
    public function destroy(Request $request)
    {
        return $this->manager()->del($request->get('key'));
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function fetch(Request $request)
    {
        return $this->manager()->fetch($request->get('key'));
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function remove(Request $request)
    {
        $type = $request->get('type');

        return $this->manager()->{$type}()->remove($request->all());
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function update(Request $request)
    {
        return $this->manager()->update($request);
    }

    /**
     * Redis console interface.
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function console(Content $content, Request $request)
    {
        $connection = $request->get('conn', 'default');

        $manager = $this->manager();

        $vars = [
            'conn' => $connection,
            'info' => $manager->getInformation(),
            'connections' => $manager->getConnections(),
        ];

        $view = 'juenfy.dcat-redis-manager::console';

        return $content->header('Redis manager')
            ->description('Connections')
            ->breadcrumb(
                ['text' => 'Redis manager', 'url' => admin_route('redis-index', ['conn' => $connection])],
                ['text' => 'Console']
            )
            ->body(Admin::view($view, $vars));
    }

    /**
     * Execute a redis command.
     *
     * @param Request $request
     *
     * @return bool|string
     */
    public function execute(Request $request)
    {
        $command = $request->get('command');

        try {
            $result = $this->manager()->execute($command);
        } catch (\Exception $exception) {
            return $this->renderException($exception);
        }

        if (is_string($result) && Str::startsWith($result, ['ERR ', 'WRONGTYPE '])) {
            return $this->renderException(new \Exception($result));
        }

        return $this->getDumpedHtml($result);
    }

    /**
     * Render exception.
     *
     * @param \Exception $exception
     *
     * @return string
     */
    protected function renderException(\Exception $exception)
    {
        return sprintf(
            "<div class='callout callout-warning'><i class='icon fa fa-warning'></i>&nbsp;&nbsp;&nbsp;%s</div>",
            str_replace("\n", '<br />', $exception->getMessage())
        );
    }

    /**
     * Get html of dumped variable.
     *
     * @param mixed $var
     *
     * @return bool|string
     */
    protected function getDumpedHtml($var)
    {
        ob_start();

        dump($var);

        $content = ob_get_contents();

        ob_get_clean();

        return substr($content, strpos($content, '<pre '));
    }

    /**
     * Get the redis manager instance.
     *
     * @return RedisManager
     */
    protected function manager()
    {
        $conn = \request()->get('conn');

        return RedisManager::instance($conn);
    }
}
