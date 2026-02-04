<?php
/**
 * TimeProvider: バックエンドから時刻を供給する（テスト時は差し替え可能）。
 * DB の datetime('now') は使わず、アプリ側で時刻を渡す。
 */
class TimeProvider
{
    /** @var \DateTimeZone|null 未設定時は Asia/Tokyo */
    private $timezone;

    public function __construct(?\DateTimeZone $timezone = null)
    {
        $this->timezone = $timezone ?? new \DateTimeZone('Asia/Tokyo');
    }

    /**
     * 現在日時を SQLite 用フォーマット（Y-m-d H:i:s）で返す。
     */
    public function now(): string
    {
        return (new \DateTimeImmutable('now', $this->timezone))->format('Y-m-d H:i:s');
    }
}
