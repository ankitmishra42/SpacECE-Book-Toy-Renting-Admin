<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Output\BufferedOutput;

class InstallController extends Controller
{
    public function configSetup(Request $request, Redirector $redirect)
    {
        $rules = config('installer.environment.form.rules');
        $messages = [
            'environment_custom.required_if' => trans('installer_messages.environment.wizard.form.name_required'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return $redirect->route('LaravelInstaller::environmentWizard')->withInput()->withErrors($validator->errors());
        }

        if (!$this->checkDatabaseConnection($request)) {
            return $redirect->route('LaravelInstaller::environmentWizard')->withInput()->withErrors([
                'database_connection' => trans('installer_messages.environment.wizard.form.db_connection_failed'),
            ]);
        }

        $results = $this->saveFileWizard($request);

        return $redirect->route('LaravelInstaller::database')
            ->with(['results' => $results]);
    }

    public function saveClassic(Request $input, Redirector $redirect)
    {
        $message = $this->saveFileClassic($input);

        return $redirect->route('LaravelInstaller::environmentClassic')
            ->with(['message' => $message]);
    }

    public function database()
    {
        $previousAppServiceProvider = base_path('app/Providers/AppServiceProvider.php');
        $newRouteAppServiceProvider = base_path('app/Http/Core/AppServiceProvider.txt');
        copy($newRouteAppServiceProvider, $previousAppServiceProvider);

        $response = $this->migrate();
        $this->restoreData();

        return redirect()->route('LaravelInstaller::final')
            ->with(['message' => $response]);
    }

    private function migrate()
    {
        $outputLog = new BufferedOutput;
        try {
            Artisan::call('migrate:fresh', ['--force' => true], $outputLog);
        } catch (Exception $e) {
            return $this->response($e->getMessage(), 'error', $outputLog);
        }

        return $this->seed($outputLog);
    }

    private function seed(BufferedOutput $outputLog)
    {
        try {
            shell_exec('php ../artisan db:seed --force');
            shell_exec('php ../artisan passport:install');
            shell_exec('php ../artisan storage:link');
        } catch (Exception $e) {
            return $this->response($e->getMessage(), 'error', $outputLog);
        }

        return $this->response(trans('installer_messages.final.finished'), 'success', $outputLog);
    }

    private function restoreData()
    {
        $previousRouteServiceProvier = base_path('app/Providers/RouteServiceProvider.php');
        $newRouteServiceProvier = base_path('app/Http/Core/RouteServiceProvider.txt');
        copy($newRouteServiceProvier, $previousRouteServiceProvier);

        $routeWeb = base_path('routes/web.php');
        $newRouteWeb = base_path('app/Http/Core/web.txt');
        copy($newRouteWeb, $routeWeb);

        $routeAdmin = base_path('routes/api/admin.php');
        $newRouteAdmin = base_path('app/Http/Core/admin.txt');
        copy($newRouteAdmin, $routeAdmin);

        $routeCustomer = base_path('routes/api/customer.php');
        $newRouteCustomer = base_path('app/Http/Core/customer.txt');
        copy($newRouteCustomer, $routeCustomer);

        $routeDriver = base_path('routes/api/driver.php');
        $newRouteDriver = base_path('app/Http/Core/driver.txt');
        copy($newRouteDriver, $routeDriver);

        $routeSeller = base_path('routes/api/seller.php');
        $newRouteSeller = base_path('app/Http/Core/seller.txt');
        copy($newRouteSeller, $routeSeller);
    }

    private function response($message, $status, BufferedOutput $outputLog)
    {
        return [
            'status' => $status,
            'message' => $message,
            'dbOutputLog' => $outputLog->fetch(),
        ];
    }

    private function checkDatabaseConnection(Request $request)
    {
        $connection = $request->input('database_connection');

        $settings = config("database.connections.$connection");

        config([
            'database' => [
                'default' => $connection,
                'connections' => [
                    $connection => array_merge($settings, [
                        'driver' => $connection,
                        'host' => $request->input('database_hostname'),
                        'port' => $request->input('database_port'),
                        'database' => $request->input('database_name'),
                        'username' => $request->input('database_username'),
                        'password' => $request->input('database_password'),
                    ]),
                ],
            ],
        ]);

        DB::purge();


        try {
            DB::connection()->getPdo();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    private function saveFileClassic(Request $input)
    {
        $message = trans('installer_messages.environment.success');

        try {
            file_put_contents(base_path('.env'), $input->get('envConfig'));
        } catch (Exception $e) {
            $message = trans('installer_messages.environment.errors');
        }

        return $message;
    }
    private function saveFileWizard(Request $request)
    {
        $results = trans('installer_messages.environment.success');
        $envFileData =
            'APP_NAME=\'' . $request->app_name . "'\n" .
            'APP_ENV=production' . "\n" .
            'APP_KEY=' . 'base64:' . base64_encode(str()->random(32)) . "\n" .
            'APP_DEBUG=' . $request->app_debug . "\n" .
            'APP_URL=' . $request->app_url . "\n\n" .
            'APP_TIMEZONE=America/New_York' . "\n" .
            'DB_CONNECTION=' . $request->database_connection . "\n" .
            'DB_HOST=' . $request->database_hostname . "\n" .
            'DB_PORT=' . $request->database_port . "\n" .
            'DB_DATABASE=' . $request->database_name . "\n" .
            'DB_USERNAME=' . $request->database_username . "\n" .
            'DB_PASSWORD=' . $request->database_password . "\n\n" .
            'FILESYSTEM_DISK=public' . "\n\n" .
            'BROADCAST_DRIVER=log' . "\n" .
            'CACHE_DRIVER=file' . "\n" .
            'SESSION_DRIVER=file' . "\n" .
            'SESSION_LIFETIME=120' . "\n" .
            'QUEUE_CONNECTION=sync' . "\n\n" .
            'MAIL_MAILER=smtp' . "\n\n" .
            'MAIL_HOST=smtp.gmail.com' . "\n" .
            'MAIL_PORT=645' . "\n" .
            'MAIL_USERNAME=null' . "\n" .
            'MAIL_PASSWORD=null' . "\n" .
            'MAIL_ENCRYPTION=tls' . "\n" .
            'MAIL_FROM_ADDRESS=null' . "\n" .
            'MAIL_FROM_NAME="${APP_NAME}"' . "\n" .
            'MAIL_TWO_STEP_VERIFACATION=0' . "\n\n" .
            'STRIPE_KEY=' . "\n" .
            'STRIPE_SECRET=' . "\n\n" .
            'FCM_SERVER_KEY=' . $request->fcm_server_key."\n\n".
            'SMS_BASE_URL=' ."\n".
            'SMS_USER_NAME=' ."\n".
            'SMS_PASSWORD=' ."\n".
            'SMS_SOURCE=' ."\n".
            'SMS_TWO_STEP_VERIFACATION=0' ."\n".
            'SID=' ."\n\n".
            'MAP_API_KEY=' ."\n\n";
        try {
            file_put_contents(base_path('.env'), $envFileData);
        } catch (Exception $e) {
            $results = trans('installer_messages.environment.errors');
        }

        return $results;
    }
}
