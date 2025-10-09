<?php
namespace App\Http\Middleware;
use Illuminate\Http\Middleware\TrustHosts as Middleware;
class TrustHosts extends Middleware {
    protected function hosts(): array {
        return [
            '15-168-43-164\.sslip\.io',
            '15\.168\.43\.164',
            $this->allSubdomainsOfApplicationUrl(),
        ];
    }
}
