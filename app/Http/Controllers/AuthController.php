<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * ログイン画面を表示
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * ログイン処理
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('menu')->with('success', 'ログインしました。');
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => 'ログインに失敗しました。メールアドレスまたはパスワードが間違っています。']);
    }

    /**
     * 新規登録画面を表示
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * 新規登録処理（強化版バリデーション）
     */
    public function store(Request $request)
    {
        // カスタムバリデーションルール
        $validator = Validator::make($request->all(), [
            'user_name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[a-zA-Z0-9\p{Hiragana}\p{Katakana}\p{Han}ー\s]+$/u'
            ],
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:255',
                'unique:users,email'
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:128',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
            'password_confirmation' => 'required|string|min:8'
        ], [
            // カスタムエラーメッセージ
            'user_name.required' => 'ユーザー名は必須です。',
            'user_name.min' => 'ユーザー名は2文字以上で入力してください。',
            'user_name.max' => 'ユーザー名は50文字以下で入力してください。',
            'user_name.regex' => 'ユーザー名は英数字、ひらがな、カタカナ、漢字のみ使用できます。',
            
            'email.required' => 'メールアドレスは必須です。',
            'email.email' => '有効なメールアドレスを入力してください。',
            'email.unique' => 'このメールアドレスは既に登録されています。',
            'email.max' => 'メールアドレスは255文字以下で入力してください。',
            
            'password.required' => 'パスワードは必須です。',
            'password.min' => 'パスワードは8文字以上で入力してください。',
            'password.max' => 'パスワードは128文字以下で入力してください。',
            'password.confirmed' => 'パスワード確認が一致しません。',
            'password.regex' => 'パスワードは大文字・小文字・数字を含む必要があります。',
            
            'password_confirmation.required' => 'パスワード確認は必須です。',
            'password_confirmation.min' => 'パスワード確認は8文字以上で入力してください。'
        ]);

        // バリデーション失敗時
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except(['password', 'password_confirmation']));
        }

        try {
            // ユーザー作成
            $user = User::create([
                'name' => $request->user_name, // データベースのカラム名に合わせる
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 登録成功ログ
            \Log::info('New user registered', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip_address' => $request->ip()
            ]);

            return redirect()->route('login')
                ->with('success', '✅ アカウント登録が完了しました。ログインしてください。');

        } catch (\Exception $e) {
            // エラーログ記録
            \Log::error('User registration failed', [
                'email' => $request->email,
                'error' => $e->getMessage(),
                'ip_address' => $request->ip()
            ]);

            return redirect()->back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->withErrors(['general' => '登録処理中にエラーが発生しました。しばらく後にお試しください。']);
        }
    }

    /**
     * ログアウト処理
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        
        // ログアウトログ記録
        if ($user) {
            \Log::info('User logged out', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip_address' => $request->ip()
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')
            ->with('success', '👋 ログアウトしました。');
    }

    /**
     * アカウント情報確認（管理用）
     */
    public function profile()
    {
        $user = Auth::user();
        return view('auth.profile', compact('user'));
    }

    /**
     * アカウント情報更新
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'user_name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[a-zA-Z0-9\p{Hiragana}\p{Katakana}\p{Han}ー\s]+$/u'
            ],
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:255',
                'unique:users,email,' . $user->id
            ],
            'current_password' => 'required_with:new_password',
            'new_password' => [
                'nullable',
                'string',
                'min:8',
                'max:128',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        // 現在のパスワード確認（新しいパスワードが指定されている場合）
        if ($request->new_password) {
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->withErrors([
                    'current_password' => '現在のパスワードが正しくありません。'
                ]);
            }
        }

        try {
            // 情報更新
            $user->name = $request->user_name;
            $user->email = $request->email;
            
            if ($request->new_password) {
                $user->password = Hash::make($request->new_password);
            }
            
            $user->save();

            return redirect()->back()
                ->with('success', 'プロフィールが更新されました。');

        } catch (\Exception $e) {
            \Log::error('Profile update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->withErrors(['general' => '更新処理中にエラーが発生しました。']);
        }
    }
}