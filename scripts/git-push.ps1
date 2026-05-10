#!/usr/bin/env pwsh
<#
.SYNOPSIS
    git add -A, commit (message tự sinh), rồi push — chỉ cần chạy script.

.DESCRIPTION
    Mặc định: add toàn bộ, commit với message TỰ TẠO (sync + giờ + nhánh) — bạn không phải gõ gì.
    - Muốn tự đặt message (tuỳ chọn): -m "nội dung"
    - Chỉ push, không add/commit: -PushOnly
    Upstream lần đầu: -SetUpstream

.EXAMPLE
    .\scripts\git-push.ps1
.EXAMPLE
    .\scripts\git-push.ps1 -PushOnly
.EXAMPLE
    .\scripts\git-push.ps1 -m "Sửa menu"
.EXAMPLE
    .\scripts\git-push.ps1 -SetUpstream
.EXAMPLE
    .\scripts\git-push.ps1 -- --force-with-lease
#>
param(
    [string] $Remote = 'origin',
    [switch] $SetUpstream,
    [switch] $PushOnly,
    [Alias('m')]
    [string] $Message,
    [Parameter(ValueFromRemainingArguments = $true)]
    [string[]] $GitPassThrough
)

Set-StrictMode -Version Latest
$ErrorActionPreference = 'Stop'

$repoRoot = git rev-parse --show-toplevel 2>$null
if (-not $repoRoot) {
    Write-Error 'Không phải trong thư mục git (không tìm thấy .git).'
    exit 1
}

Set-Location -LiteralPath $repoRoot

$branch = (git rev-parse --abbrev-ref HEAD).Trim()
if ($branch -eq 'HEAD') {
    Write-Error 'Bạn đang ở detached HEAD; checkout một nhánh rồi push.'
    exit 1
}

Write-Host "Repo: $repoRoot" -ForegroundColor DarkGray

if (-not $PushOnly) {
    Write-Host 'git add -A' -ForegroundColor Cyan
    & git add -A
    if ($LASTEXITCODE -ne 0) {
        exit $LASTEXITCODE
    }

    $porcelain = git status --porcelain 2>$null
    if (-not $porcelain) {
        Write-Host 'Không có thay đổi; bỏ qua commit.' -ForegroundColor Yellow
    }
    else {
        $stamp = Get-Date -Format 'yyyy-MM-dd HH:mm'
        if (-not [string]::IsNullOrWhiteSpace($Message)) {
            $commitMsg = $Message.Trim()
        }
        else {
            $commitMsg = "sync: $stamp [$branch]"
            Write-Host "Message tự tạo (không cần -m): $commitMsg" -ForegroundColor DarkGray
        }

        Write-Host "git commit -m `"$commitMsg`"" -ForegroundColor Cyan
        & git commit -m $commitMsg
        if ($LASTEXITCODE -ne 0) {
            exit $LASTEXITCODE
        }
    }
}

Write-Host "Push: $branch -> $Remote" -ForegroundColor Cyan

$cmd = @('push', $Remote, $branch)
if ($SetUpstream) {
    $cmd = @('push', '-u', $Remote, $branch)
}

if ($GitPassThrough -and $GitPassThrough.Count -gt 0) {
    $cmd += $GitPassThrough
}

& git @cmd
exit $LASTEXITCODE
