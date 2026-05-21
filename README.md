<p align="center">
  <img src="assets/images/deepseek.svg" alt="DeepSeek" width="96" height="96" />
</p>

# AI Provider for DeepSeek

[![WordPress](https://img.shields.io/badge/WordPress-6.9%2B-21759B?logo=wordpress&logoColor=white)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?logo=php&logoColor=white)](https://www.php.net/)

这是一个用于 WordPress AI Client / Connectors 的 DeepSeek 对接插件。

安装后，后台 Connectors 页面会出现 `DeepSeek`。用户只需要填写 DeepSeek API Key，插件会自动获取 DeepSeek 当前支持的模型，并把这些模型提供给 WordPress AI 功能选择。

仓库地址：<https://github.com/DearLicy/ai-provider-for-deepseek>

## 功能特点

- 接入 DeepSeek 官方接口。
- 使用 WordPress Connectors 管理 API Key。
- 自动获取 DeepSeek 当前可用模型。
- 支持 `deepseek-chat`、`deepseek-reasoner` 以及 DeepSeek 后续通过模型接口返回的新模型。
- 适配 WordPress AI Client 的文本生成和多轮对话能力。

## 环境要求

- WordPress `6.9+`
- PHP `7.4+`
- WordPress AI Client / Connectors 可用
- DeepSeek API Key

## 安装

### 方式一：从 GitHub 克隆

进入 WordPress 插件目录：

```bash
cd wp-content/plugins
```

克隆仓库：

```bash
git clone https://github.com/DearLicy/ai-provider-for-deepseek.git
```

然后进入 WordPress 后台启用插件：

```text
插件 → 已安装插件 → AI Provider for DeepSeek → 启用
```

### 方式二：手动上传

下载本仓库后，将插件目录上传到：

```text
wp-content/plugins/ai-provider-for-deepseek
```

然后在 WordPress 后台启用 `AI Provider for DeepSeek`。

## 使用方法

启用插件后，进入 WordPress 后台的 Connectors 页面。

找到：

```text
DeepSeek
```

填写你的 DeepSeek API Key，然后保存。

保存时插件只检查密钥是否已填写，不强制在线请求 DeepSeek；这样可以避免服务器网络临时不可达导致 Connectors 拒绝保存。

完成后，WordPress AI 功能在选择模型时会按需获取 DeepSeek 当前可用模型。

## 模型说明

插件不会在代码里固定写死模型列表。

DeepSeek 官方模型接口返回什么模型，插件就会提供什么模型给用户选择。这样 DeepSeek 后续新增模型时，通常不需要更新插件代码。

常见模型包括：

```text
deepseek-chat
deepseek-reasoner
```

实际可选模型以你的 DeepSeek API Key 当前可访问的模型列表为准。

## 常见问题

### 这个插件需要单独配置接口地址吗？

不需要。

插件固定使用 DeepSeek 官方接口 `https://api.deepseek.com`，用户只需要填写 DeepSeek API Key。

### 为什么没有插件设置页？

这个插件只做一件事：把 DeepSeek 接入 WordPress AI Client。

API Key 已经由 WordPress Connectors 负责管理，模型列表也会自动获取，所以不需要额外设置页。

### 模型列表为什么没有显示？

通常有三个原因：

1. DeepSeek API Key 没有填写或填写错误。
2. 当前服务器无法请求 DeepSeek 官方接口。
3. WordPress AI Client / Connectors 没有正常启用。

### 支持 deepseek-reasoner 吗？

支持。

只要 DeepSeek 的模型接口返回 `deepseek-reasoner`，插件就会把它提供给 WordPress AI 功能选择。

### 支持图片生成吗？

当前版本只支持文本生成和多轮对话，不声明图片生成能力。

## 维护者

- 作者：李初一
- GitHub：[@DearLicy](https://github.com/DearLicy)
- 仓库：[DearLicy/ai-provider-for-deepseek](https://github.com/DearLicy/ai-provider-for-deepseek)