<?php

namespace App\Library\Enums\Orders;

use BenSampo\Enum\Enum;

class Status extends Enum
{
	const Placed = 'placed';
	const Pending = 'pending';
	const PendingDispatch = 'pending-dispatch';
	const ReadyForDispatch = 'ready-for-dispatch';
	const Dispatched = 'dispatched';
	const OutForDelivery = 'out-for-delivery';
	const Rescheduled = 'rescheduled';
	const Delivered = 'delivered';
	const Cancelled = 'cancelled';
	const RefundProcessing = 'refund-processing';
	const Refunded = 'refunded';
	const ReturnInitiated = 'return-initiated';
	const ReturnApproved = 'return-approved';
	const ReturnDisapproved = 'return-disapproved';
	const ReturnCompleted = 'return-completed';
	const NotAvailable = 'N/A';

	public static function transitions (Status $status): array
	{
		switch ($status->value) {
			case self::Placed:
				return [
					self::getKey(self::ReadyForDispatch) => self::ReadyForDispatch,
					self::getKey(self::Dispatched) => self::Dispatched,
					self::getKey(self::Cancelled) => self::Cancelled,
				];

			case self::ReadyForDispatch:
				return [
					self::getKey(self::Dispatched) => self::Dispatched,
					self::getKey(self::Cancelled) => self::Cancelled,
				];

			case self::Dispatched:
				return [
					self::getKey(self::OutForDelivery) => self::OutForDelivery,
					self::getKey(self::Rescheduled) => self::Rescheduled,
					self::getKey(self::Cancelled) => self::Cancelled,
				];

			case self::Rescheduled:
				return [
					self::getKey(self::OutForDelivery) => self::OutForDelivery,
					self::getKey(self::Cancelled) => self::Cancelled,
				];

			case self::OutForDelivery:
				return [
					self::getKey(self::Rescheduled) => self::Rescheduled,
					self::getKey(self::Delivered) => self::Delivered,
					self::getKey(self::Cancelled) => self::Cancelled,
				];

			case self::Cancelled:
				return [
					self::getKey(self::RefundProcessing) => self::RefundProcessing,
					self::getKey(self::Refunded) => self::Refunded,
				];

			case self::RefundProcessing:
				return [
					self::getKey(self::Refunded) => self::Refunded,
				];
			case self::PendingDispatch:
				return [
					self::getKey(self::Dispatched) => self::Dispatched,
					self::getKey(self::Delivered) => self::Delivered,
					self::getKey(self::Cancelled) => self::Cancelled,
					self::getKey(self::ReadyForDispatch) => self::ReadyForDispatch,
				];

			default:
				return [
					self::getKey(self::NotAvailable) => self::NotAvailable,
				];
		}
	}
}