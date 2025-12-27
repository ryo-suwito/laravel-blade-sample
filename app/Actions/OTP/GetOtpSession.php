<?php

namespace App\Actions\OTP;

use Illuminate\Support\Facades\Cache;

class GetOtpSession
{
    protected string $sentCountKey = 'otp.sent-count:%user';

    protected string $expiredAtKey = 'otp.expired-at:%user';

    protected string $enableInputKey = 'otp.enable-input:%user';

    protected string $verifiedKey = 'otp.verified:%user';

    protected ?int $userId = null;

    protected int $sentCount = 0;

    protected ?string $expiredAt = null;

    protected bool $enableInput = true;

    public function __construct(int $userId)
    {
        $this->userId = $userId;

        $this->sentCount = Cache::get($this->cacheKey($this->sentCountKey), 0);
        $this->expiredAt = Cache::get($this->cacheKey($this->expiredAtKey));
        $this->enableInput = Cache::get($this->cacheKey($this->enableInputKey), true);
    }

    protected function cacheKey($key)
    {
        return str_replace('%user', $this->userId, $key);
    }

    /**
     * @return void
     */
    public function incrementCount()
    {
        $this->sentCount += 1;

        Cache::put($this->cacheKey($this->sentCountKey),$this->sentCount, now()->endOfDay());
    }

    /**
     * @param string $expiredAt
     * @return void
     */
    public function setExpiredAt(string $expiredAt)
    {
        $this->expiredAt = $expiredAt;

        Cache::put(
            $this->cacheKey($this->expiredAtKey),
            $this->expiredAt,
            now()->endOfDay()
        );
    }

    /**
     * @param boolean $enabledInput
     * @return void
     */
    public function setEnableInput(bool $enabledInput)
    {
        $this->enableInput = $enabledInput;

        Cache::put(
            $this->cacheKey($this->enableInputKey),
            $this->enableInput,
            now()->endOfDay()
        );
    }

    /**
     * @param string $expiredAt
     * @return void
     */
    public function update(string $expiredAt, bool $enableInput = true)
    {
        $this->incrementCount();
        $this->setExpiredAt($expiredAt);
        $this->setEnableInput($enableInput);
    }

    /**
     * @return void
     */
    public function clear()
    {
        $this->sentCount = 0;
        $this->expiredAt = null;
        $this->enableInput = true;

        Cache::forget($this->cacheKey($this->sentCountKey));
        Cache::forget($this->cacheKey($this->enableInputKey));

        $this->unverified();
    }

    /**
     * @return void
     */
    public function verified()
    {
        session()->put($this->cacheKey($this->verifiedKey), true);
    }

    /**
     * @return void
     */
    public function unverified()
    {
        session()->put($this->cacheKey($this->verifiedKey), false);
    }

    /**
     * @return int
     */
    public function getSentCount()
    {
        return $this->sentCount;
    }

    /**
     * @return string
     */
    public function getExpiredAt()
    {
        return $this->expiredAt;
    }

    /**
     * @return bool
     */
    public function hasExpiredAt()
    {
        return ! is_null($this->expiredAt);
    }

    /**
     * @return \Illuminate\Support\Carbon
     */
    public function delayUntil()
    {
        $time = now()
            ->parse($this->expiredAt)
            ->timezone(config('app.timezone'));

        if ($this->sentCount > 3) {
            $delay = ($this->sentCount - 3) * 180;

            return $time->addSeconds($delay);
        }

        return $time;
    }

    /**
     * @return bool
     */
    public function canSendOtp()
    {
        return is_null($this->expiredAt) || $this->delayUntil()->lessThan(now());
    }

    /**
     * @return bool
     */
    public function isEnabledInput()
    {
        return $this->enableInput;
    }

    /**
     * @return bool
     */
    public function isVerified()
    {
        return session()->get($this->cacheKey($this->verifiedKey), false);
    }
}
