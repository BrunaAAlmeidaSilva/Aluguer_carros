<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'g-recaptcha-response' => ['required', 'string', function ($attribute, $value, $fail) {
                $secret = config('recaptcha.secret_key');
                $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $value . '&remoteip=' . request()->ip());
                $result = json_decode($response, true);
                if (!isset($result['success']) || $result['success'] !== true) {
                    $fail('reCAPTCHA validation failed.');
                }
            }],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        // Guardar dados da reserva antes de regenerar a sessão
        $dadosReserva = [
            'local_levantamento' => session('local_levantamento'),
            'local_devolucao' => session('local_devolucao'),
            'data_hora_levantamento' => session('data_hora_levantamento'),
            'data_hora_devolucao' => session('data_hora_devolucao'),
            'bem_id' => session('bem_id'),
        ];

        Auth::login($user);
        $request->session()->regenerate();
        // Repõe os dados na nova sessão
        foreach ($dadosReserva as $key => $value) {
            if ($value) {
                session([$key => $value]);
            }
        }

        // Redirecionar para pagamento se reserva estiver em progresso
        if (session()->has('reservation_in_progress') && session()->has('reserva_id')) {
            $reservaId = session('reserva_id');
            session()->forget(['reservation_in_progress', 'reserva_id']);
            return redirect()->route('pagamentos.show', ['reserva' => $reservaId]);
        }
        return redirect(route('dashboard', absolute: false));
    }
}
