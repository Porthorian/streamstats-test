<?php
declare(strict_types=1);

namespace Porthorian\StreamStats\Modules\Streams;

use Porthorian\EntityOrm\Model\Model;
use Porthorian\Utility\Time\TimeCodes;

class Stream extends Model
{
	protected int $STREAMID = 0;
	protected string $TWITCHSTREAMID = '';
	protected ?int $GAMEID = null;
	protected string $streamer_name = '';
	protected string $stream_title = '';
	protected int $viewers = 0;
	protected string $date_started = TimeCodes::DATE_ZERO;
	protected string $last_seen = TimeCodes::DATE_ZERO;

	public function getStreamId() : int
	{
		return $this->STREAMID;
	}

	public function setStreamId(int $stream_id) : void
	{
		$this->STREAMID = $stream_id;
	}

	public function getTwitchStreamId() : string
	{
		return $this->TWITCHSTREAMID;
	}

	public function setTwitchStreamId(string $twitch_stream_id) : void
	{
		$this->TWITCHSTREAMID = $twitch_stream_id;
	}

	public function getGameId() : ?int
	{
		return $this->GAMEID;
	}

	public function setGameId(?int $id) : void
	{
		$this->GAMEID = $id;
	}

	public function getStreamerName() : string
	{
		return $this->streamer_name;
	}

	public function setStreamerName(string $streamer_name) : void
	{
		$this->streamer_name = $streamer_name;
	}

	public function getStreamTitle() : string
	{
		return $this->stream_title;
	}

	public function setStreamTitle(string $title) : void
	{
		$this->stream_title = $title;
	}

	public function getViewers() : int
	{
		return $this->viewers;
	}

	public function setViewers(int $viewers) : void
	{
		$this->viewers = $viewers;
	}

	public function getDateStarted() : string
	{
		return $this->date_started;
	}

	public function setDateStarted(string $date_started) : void
	{
		$this->date_started = date(TimeCodes::DATEFORMAT_STANDARD, strtotime($date_started));
	}

	public function getLastSeen() : string
	{
		return $this->last_seen;
	}

	public function setLastSeen(string $last_seen) : void
	{
		$this->last_seen = $last_seen;
	}

	////
	// Interface routines
	////

	public function getPrimaryKey() : string|int
	{
		return $this->getStreamId();
	}

	public function setPrimaryKey(string|int $pk_value) : void
	{
		$this->setStreamId($pk_value);
	}

	public function toArray() : array
	{
		return [
			'STREAMID'       => $this->getStreamId(),
			'TWITCHSTREAMID' => $this->getTwitchStreamId(),
			'GAMEID'         => $this->getGameId(),
			'streamer_name'  => $this->getStreamerName(),
			'stream_title'   => $this->getStreamTitle(),
			'viewers'        => $this->getViewers(),
			'date_started'   => $this->getDateStarted(),
			'last_seen'      => $this->getLastSeen(),
		];
	}

	public function toPublicArray() : array
	{
		$array = $this->toArray();
		unset($array['last_seen']);
		return $array;
	}

	public function getEntityPath() : string
	{
		return '\Porthorian\StreamStats\Modules\Streams\StreamEntity';
	}
}
