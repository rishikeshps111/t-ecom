<?php

use App\Models\Announcement;
use App\Models\FinancialYear;
use App\Models\Prefix;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Auth;

if (!function_exists('system_setting')) {
    /**
     * Get system setting value by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function system_setting($key, $default = null)
    {
        $setting = SystemSetting::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
}

if (!function_exists('get_prefix')) {
    /**
     * Get prefix value by module name
     *
     * @param string $module
     * @param mixed $default
     * @return string|null
     */
    function get_prefix($module, $default = null)
    {
        $prefix = Prefix::where('module', $module)->first();
        return $prefix ? $prefix->prefix : $default;
    }
}


if (!function_exists('route_with_query')) {
    function route_with_query(string $route, array $params = [])
    {
        return route($route, array_filter($params, function ($value) {
            return !is_null($value) && $value !== '';
        }));
    }
}

if (!function_exists('active_financial_year_start')) {
    function active_financial_year_start()
    {
        $activeYear = FinancialYear::where('is_active', 1)->first();

        if ($activeYear) {
            return $activeYear->year; // already YYYY
        }

        // Fallback to current year
        return date('Y');
    }
}

if (!function_exists('unreadMessagesCount')) {
    function unreadMessagesCount()
    {
        $user = auth()->user();

        if ($user->hasRole('Super Admin')) {
            // Count all unread messages sent by customers
            return \App\Models\Conversation::whereHas('chat', function ($q) {
                $q->whereNotNull('user_id'); // chats from customers
            })->where('send_by', 'user')
                ->where('is_read', false)
                ->count();
        }

        if ($user->hasRole('Customer')) {
            // Count all unread chats sent by admin
            return \App\Models\Conversation::whereHas('chat', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->where('send_by', 'admin')
                ->where('is_read', false)
                ->count();
        }

        return 0;
    }
}

if (!function_exists('unreadConversationCount')) {
    /**
     * Get the number of unread conversations for a specific message
     */
    function unreadConversationCount($messageId)
    {
        $user = auth()->user();

        if ($user->hasRole('Super Admin')) {
            // Super Admin: unread conversations sent by customers
            return \App\Models\Conversation::where('message_id', $messageId)
                ->where('send_by', 'user')
                ->where('is_read', false)
                ->count();
        }

        if ($user->hasRole('Customer')) {
            // Customer: unread conversations sent by admin
            return \App\Models\Conversation::where('message_id', $messageId)
                ->where('send_by', 'admin')
                ->where('is_read', false)
                ->count();
        }

        return 0;
    }
}
if (!function_exists('unreadMessagesByUser')) {
    /**
     * Get unread messages grouped by user (for Super Admin)
     * or by message (for Customer)
     */
    function unreadMessagesByUser()
    {
        $user = auth()->user();

        if ($user->hasRole('Super Admin')) {
            $conversations = \App\Models\Conversation::where('send_by', 'user')
                ->where('is_read', false)
                ->with(['chat.user']) // eager load user
                ->get()
                ->filter(fn($conv) => $conv->chat->id); // make sure chat exists

            // Order by chat priority manually
            return $conversations->sortByDesc(fn($conv) => match ($conv->chat->priority ?? 'low') {
                'high' => 3,
                'medium' => 2,
                'low' => 1,
                default => 0
            });
        }

        if ($user->hasRole(['Customer', 'Planner', 'Production Staff'])) {
            $conversations = \App\Models\Conversation::where('send_by', 'admin')
                ->where('is_read', false)
                ->whereHas('chat', fn($q) => $q->where('user_id', $user->id))
                ->with('chat')
                ->get();

            return $conversations->filter(fn($conv) => $conv->chat->id)
                ->sortByDesc(fn($conv) => match ($conv->chat->priority ?? 'low') {
                    'high' => 3,
                    'medium' => 2,
                    'low' => 1,
                    default => 0
                });
        }

        return collect();
    }
}


if (!function_exists('amount_in_words')) {
    /**
     * Convert a number to English words with Ringgit and Cents.
     *
     * @param float $amount
     * @param string $currencyName
     * @param string $subCurrencyName
     * @return string
     */
    function amount_in_words($amount, $currencyName = 'Ringgit', $subCurrencyName = 'Cents')
    {
        $formatter = new \NumberFormatter('en', \NumberFormatter::SPELLOUT);

        $integerPart = floor($amount);
        $decimalPart = round(($amount - $integerPart) * 100);

        $words = ucfirst($formatter->format($integerPart)) . " {$currencyName}";

        if ($decimalPart > 0) {
            $words .= " and " . $formatter->format($decimalPart) . " {$subCurrencyName}";
        }

        return $words;
    }
}


if (! function_exists('unreadAnnouncement')) {

    function unreadAnnouncement()
    {
        if (!Auth::check()) {
            return 0;
        }

        $userId = Auth::id();

        return Announcement::withoutGlobalScopes()
            ->Where('schedule_date', '<=', \Carbon\Carbon::today())
            ->where(function ($q) use ($userId) {
                $q->where('type', 'public')
                    ->whereDoesntHave('users', function ($u) use ($userId) {
                        $u->where('announcement_users.user_id', $userId);
                    });
            })
            ->orWhere(function ($q) use ($userId) {
                $q->where('type', 'private')
                    ->whereHas('users', function ($u) use ($userId) {
                        $u->where('announcement_users.user_id', $userId)
                            ->whereNull('announcement_users.read_at');
                    });
            })
            ->orderByRaw("
                CASE priority
                    WHEN 'high' THEN 1
                    WHEN 'medium' THEN 2
                    WHEN 'low' THEN 3
                    ELSE 4
                END
            ")
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
